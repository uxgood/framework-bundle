<?php

namespace UxGood\Bundle\FrameworkBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\EventDispatcher\DependencyInjection\RegisterListenersPass;

class FrameworkBundle extends Bundle
{

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $hotPathEvents = array(
            KernelEvents::REQUEST,
            KernelEvents::CONTROLLER,
            KernelEvents::CONTROLLER_ARGUMENTS,
            KernelEvents::RESPONSE,
            KernelEvents::FINISH_REQUEST,
        );

        $container->addCompilerPass((new RegisterListenersPass())->setHotPathEvents($hotPathEvents), PassConfig::TYPE_BEFORE_REMOVING);
    }
}
