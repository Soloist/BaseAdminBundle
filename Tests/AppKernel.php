<?php

namespace Soloist\Bundle\BaseAdminBundle\Tests;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    /**
     * Returns an array of bundles to registers.
     *
     * @return array An array of bundle instances.
     *
     * @api
     */
    public function registerBundles()
    {
        return array(
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new \Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new \Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new \Soloist\Bundle\BaseAdminBundle\SoloistBaseAdminBundle(),
        );
    }

    /**
     * Loads the container configuration
     *
     * @param LoaderInterface $loader A LoaderInterface instance
     *
     * @api
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/stubs/app_config.yml');
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir().'/'.Kernel::VERSION.'/soloist_core/cache/'.$this->environment;
    }

    public function getLogDir()
    {
        return sys_get_temp_dir().'/'.Kernel::VERSION.'/soloist_core/logs';
    }
}
