<?php

namespace Carew\Plugin\Authors\EventSubscriber;

use Carew\Event\CarewEvent;
use Carew\Plugin\Authors\AuthorRegistry;
use Carew\Plugin\Authors\Events as AuthorEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthorsEventSubscriber implements EventSubscriberInterface
{
    /** @var AuthorRegistry */
    private $authors;

    /**
     * @param AuthorRegistry $authors
     */
    public function __construct(AuthorRegistry $authors)
    {
        $this->authors = $authors;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(AuthorEvents::AUTHORS => 'onAuthors');
    }

    /**
     * @param CarewEvent $event
     */
    public function onAuthors(CarewEvent $event)
    {
        // @TODO use author page generator
        // @TODO update global twig environment to include authors
    }
}
