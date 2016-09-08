<?php

namespace Claudusd\Composer\Mono;

use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

class PackageRepository implements InstalledRepositoryInterface
{
    protected $packages;

    public function __construct()
    {
        $this->packages = [];
    }

    /**
     * {@inheritdoc}
     */
    public function getPackages()
    {
        return $this->packages;
    }

    /**
     * {@inheritdoc}
     */
    public function getCanonicalPackages()
    {
        return $this->getPackages();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->packages);
    }

    /**
     * {@inheritdoc}
     */
    public function addPackage(PackageInterface $package)
    {
        $this->packages[$package->getName()] = $package;
    }

    /**
     * {@inheritdoc}
     */
    public function removePackage(PackageInterface $package)
    {
        if(array_key_exists($package->getName(), $this->packages)) {
            unset($this->packages[$package->getName()]);
        }
    }

    public function reload()
    {
        // TODO: Implement reload() method.
    }

    public function write()
    {
        // TODO: Implement write() method.
    }

    public function findPackage($name, $constraint)
    {
        // TODO: Implement findPackage() method.
    }

    public function hasPackage(PackageInterface $package)
    {
        // TODO: Implement hasPackage() method.
    }

    public function search($query, $mode = 0)
    {
        // TODO: Implement search() method.
    }

    public function findPackages($name, $constraint = null)
    {
        // TODO: Implement findPackages() method.
    }
}