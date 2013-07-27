<?php

namespace Carew\Plugin\Authors;

use Carew\Carew;
use Carew\ExtensionInterface;
use Carew\Plugin\Authors\Entity\Author;
use Carew\Plugin\Authors\EventSubscriber\AuthorsEventSubscriber;
use Carew\Plugin\Authors\EventSubscriber\IndexesEventSubscriber;

class AuthorsExtension implements ExtensionInterface
{
    public function register(Carew $carew)
    {
        $config = $carew->getContainer()->offsetGet('config');

        $registry = new AuthorRegistry();

        if (isset($config['authors'])) {
            foreach ($config['authors'] as $handle => $attributes) {
                $registry->addAuthor(new Author($handle, $attributes));
            }
        }

        $carew->getEventDispatcher()->addSubscriber(
            new IndexesEventSubscriber($registry)
        );

        $carew->getEventDispatcher()->addSubscriber(
            new AuthorsEventSubscriber($registry)
        );
    }
}
