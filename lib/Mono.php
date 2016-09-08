<?php

namespace Claudusd\Composer\Mono;

use Composer\Composer;
use Composer\Config;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Mono implements PluginInterface, EventSubscriberInterface
{
    public function activate(Composer $composer, IOInterface $io)
    {

    }

    public static function getSubscribedEvents()
    {
        return [
            'post-update-cmd' => [
                ['monorepo', 0]
            ],
            'post-install-cmd' => [
                ['monorepo', 0]
            ]
        ];
    }

    public function monorepo(Event $event)
    {
        $resolver = new PackageResolver($event->getComposer()->getLocker()->getLockedRepository()->getPackages());
        $subRepoFactory = new SubRepositoryFactory($resolver);

        $finder = new Finder();
        $finder->in(realpath(getcwd()))->name('subrepo.json');

        $fs = new Filesystem();

        /** @var SubRepo[] $subRepositories */
        $subRepositories = [];

        foreach ($finder as $file) {
            $subRepositories[] = $subRepoFactory->build($file);
        }

        foreach ($subRepositories as $subRepo) {
            $event->getIO()->write($subRepo->getName());
            if ($fs->exists($subRepo->getPath().'/vendor')) {
                $fs->remove($subRepo->getPath().'/vendor');
            }

            $fs->mkdir($subRepo->getPath().'/vendor');
            foreach ($subRepo->getPackageRepository()->getPackages() as $package) {
                $name = $package->getName();
                $fs->symlink(realpath(getcwd()) . '/vendor/'.$name, $subRepo->getPath() . '/vendor/'.$name);
            }
            $composer = $event->getComposer();
            $autoloader = $composer->getAutoloadGenerator();

            $autoloader->dump(
                new Config(true, $subRepo->getPath()),
                $subRepo->getPackageRepository(),
                $subRepo->getMainPackage(),
                $composer->getInstallationManager(),
                'composer'
            );
        }
    }
}
