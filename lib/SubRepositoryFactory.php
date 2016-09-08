<?php

namespace Claudusd\Composer\Mono;

use Composer\Package\RootPackage;
use Symfony\Component\Finder\SplFileInfo;

class SubRepositoryFactory
{
    private $resolver;

    public function __construct(PackageResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * @param SplFileInfo $fileInfo
     * @return SubRepo
     */
    public function build(SplFileInfo $fileInfo)
    {
        $data = json_decode($fileInfo->getContents(), true);

        $subRepo = new SubRepo($data['name'], $fileInfo->getPath());
        $subRepo->setPackages($this->resolvePackage($data));
        $subRepo->setMainPackage($this->buildPackage($data));

        return $subRepo;
    }

    /**
     * @param array $data
     * @return array
     */
    protected function resolvePackage(array $data)
    {
        $packages = [];
        $packagesRequired = $data['require'];

        $requiredDev = false;

        if ($requiredDev) {
            $packagesRequired = array_merge($packagesRequired, $data['require-dev']);
        }

        foreach ($packagesRequired as $require) {
            $packages = array_merge($packages, $this->resolver->getAllPackage($require, $requiredDev));
        }

        return $packages;
    }

    /**
     * @param array $data
     * @return RootPackage
     */
    protected function buildPackage(array $data)
    {
        $package = new RootPackage($data['name'], 'dev', 'dev');
        $package->setAutoload($data['autoload']);
        return $package;
    }
}