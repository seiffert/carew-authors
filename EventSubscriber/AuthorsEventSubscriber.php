<?php

namespace Carew\Plugin\Authors\EventSubscriber;

use Carew\Event\CarewEvent;
use Carew\Plugin\Authors\AuthorRegistry;
use Carew\Plugin\Authors\Events as AuthorEvents;
use Carew\Plugin\Authors\Generator\AuthorIndexGenerator;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthorsEventSubscriber implements EventSubscriberInterface
{
    /** @var AuthorRegistry */
    private $authors;

    /** @var AuthorIndexGenerator  */
    private $authorPageGenerator;

    /**
     * @var \Twig_Environment
     */
    private $twig;

    /**
     * @param AuthorRegistry $authors
     */
    public function __construct(AuthorRegistry $authors, AuthorIndexGenerator $generator, \Twig_Environment $twig)
    {
        $this->authors = $authors;
        $this->authorPageGenerator = $generator;
        $this->twig = $twig;
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
        $authorPages = array();
        $authors = array();

        foreach ($this->authors->getAuthors() as $author) {
            $authorPages = array_merge(
                $authorPages,
                $this->authorPageGenerator->generateAuthorIndices($author)
            );

            $authors[] = $author;
        }

        $event->setSubject($authorPages);
        $this->twig->addGlobal('authors', $authors);
    }
}
