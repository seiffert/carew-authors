<?php

namespace Carew\Plugin\Authors;

use Carew\Carew;
use Carew\ExtensionInterface;
use Carew\Plugin\Authors\AuthorsEventSubscriber;

class AuthorsExtension implements ExtensionInterface
{
    public function register(Carew $container)
    {
        $container->getEventDispatcher()->addSubscriber(new AuthorsEventSubscriber());
    }
}
