<?php

namespace Carew\Plugin\Authors;

use Carew\Carew;
use Carew\ExtensionInterface;
use Carew\Plugin\Authors\Entity\Author;
use Carew\Plugin\Authors\EventSubscriber\AuthorsEventSubscriber;
use Carew\Plugin\Authors\EventSubscriber\IndexesEventSubscriber;
use Carew\Plugin\Authors\Generator\AuthorIndexGenerator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Finder\Finder;

class AuthorsExtension implements ExtensionInterface
{
    /**
     * @param Carew $carew
     */
    public function register(Carew $carew)
    {
        $container = $carew->getContainer();
        $registry = new AuthorRegistry();
        $eventDispatcher = $carew->getEventDispatcher();

        $config = $container->offsetGet('config');

        if (isset($config['authors'])) {
            foreach ($config['authors'] as $handle => $attributes) {
                $registry->addAuthor(new Author($handle, $attributes));
            }
        }

        $eventDispatcher->addSubscriber(
            new IndexesEventSubscriber($registry)
        );

        $eventDispatcher->addSubscriber(
            new AuthorsEventSubscriber(
                $registry,
                new AuthorIndexGenerator($container['base_dir'], new Finder()),
                $container->offsetGet('twig')
            )
        );
    }
}
