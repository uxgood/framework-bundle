<?php

namespace UxGood\Bundle\FrameworkBundle\Kernel;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\RouteCollectionBuilder;

use Symfony\Component\HttpKernel\DependencyInjection\AddAnnotatedClassesToCachePass;

trait MicroKernelTrait
{

    abstract protected function configureRoutes(RouteCollectionBuilder $routes);

    abstract protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader);

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
         $loader->load(function (ContainerBuilder $container) use ($loader) {

            $container->loadFromExtension('framework', array(
                'router' => array(
                    'resource' => 'kernel::loadRoutes',
                    'type' => 'service',
                ),
            ));

            if ($this instanceof EventSubscriberInterface) {
                $container->register('kernel', static::class)
                    ->setSynthetic(true)
                    ->setPublic(true)
                    ->addTag('kernel.event_subscriber')
                ;
            }

            $this->configureContainer($container, $loader);

            $container->addObjectResource($this);
        });

    }

    public function loadRoutes(LoaderInterface $loader)
    {

        $routes = new RouteCollectionBuilder($loader);
        $this->configureRoutes($routes);

        return $routes->build();
    }

    protected function buildContainer()
    {
        if($this->cacheConfig ?? false) {
            return parent::buildContainer();
        } else {
            $container = $this->getContainerBuilder();
            $container->addObjectResource($this);
            $this->prepareContainer($container);
            if (null !== $cont = $this->registerContainerConfiguration($this->getContainerLoader($container))) {
                $container->merge($cont);
            }
            return $container;
        }
    }
    protected function initializeContainer()
    {
        if($this->cacheConfig ?? false) {
            parent::initializeContainer();
        } else {
            $this->container = $this->buildContainer();
            $this->container->compile();
            $this->container->set('kernel', $this);
        }
    }
}
