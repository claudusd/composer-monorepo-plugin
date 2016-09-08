<?php

namespace Claudusd\Composer\Mono;

use Composer\Package\PackageInterface;

class PackageResolver
{
    /**
     * @var \Composer\Package\PackageInterface[]
     */
    protected $packages;

    /**
     * Resolver constructor.
     * @param PackageInterface[] $packages
     */
    public function __construct($packages)
    {
        $this->packages = $packages;
    }


    public function getAllPackage($packageName, $requireDev = false)
    {
        return $this->resolve($packageName, $requireDev);
    }


    /**
     * @param PackageInterface[] $packages
     * @param string $packageName
     * @param
     * @return PackageInterface[]
     */
    private function resolve($packageName, $requireDev = false)
    {
        $return = [];
        $packageFound = null;
        foreach ($this->packages as $package) {
            if ($package->getName() == $packageName) {
                $packageFound = $package;
                foreach ($package->getRequires() as $p) {
                    $return = array_merge($return, $this->resolve($p->getTarget(), $requireDev));
                }
            }
        }

        if (!$packageFound) {
            if (preg_match('/^[A-Za-z-_0-9]*\/[A-Za-z-_0-9]*$/', $packageName) === 1) {
                throw new \Exception('Package "'.$packageName.'" not found');
            }
            return $return;
        }

        $return[$packageFound->getName()] = $packageFound;
        return $return;


    }
}