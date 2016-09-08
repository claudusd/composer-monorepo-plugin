<?php

namespace Claudusd\Composer\Mono;

use Composer\Package\PackageInterface;

class SubRepo
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $name;

    /**
     * @var PackageInterface
     */
    private $mainPackage;

    /**
     * @var PackageRepository
     */
    private $packageRepository;

    /**
     * SubRepo constructor.
     * @param string $name
     * @param string $path
     */
    public function __construct($name, $path)
    {
        $this->path = $path;
        $this->name = $name;
        $this->packageRepository = new PackageRepository();
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $packages
     */
    public function setPackages($packages)
    {
        foreach ($packages as $package) {
            $this->packageRepository->addPackage($package);
        }
    }

    /**
     * @param PackageInterface $package
     */
    public function setMainPackage(PackageInterface $package)
    {
        $this->mainPackage = $package;
    }

    /**
     * @return PackageInterface
     */
    public function getMainPackage()
    {
        return $this->mainPackage;
    }

    /**
     * @return PackageRepository
     */
    public function getPackageRepository()
    {
        return $this->packageRepository;
    }
}