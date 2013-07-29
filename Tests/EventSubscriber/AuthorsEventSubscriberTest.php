<?php

namespace Carew\Plugin\Authors\Tests\EventSubscriber;

use Carew\Document;
use Carew\Event\CarewEvent;
use Carew\Plugin\Authors\AuthorRegistry;
use Carew\Plugin\Authors\Entity\Author;
use Carew\Plugin\Authors\Events as AuthorEvents;
use Carew\Plugin\Authors\EventSubscriber\AuthorsEventSubscriber;

/**
 * @group unit
 */
class AuthorsEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    public function testSubscribedEvents()
    {
        $this->assertArrayHasKey(AuthorEvents::AUTHORS, AuthorsEventSubscriber::getSubscribedEvents());
    }

    public function testOnAuthorCallsPageGenerator()
    {
        $author = new Author('test');
        $authors = new AuthorRegistry();
        $authors->addAuthor($author);
        $generator = $this->getMock('Carew\Plugin\Authors\Generator\AuthorIndexGenerator', array(), array(), '', false);

        $subscriber = new AuthorsEventSubscriber($authors, $generator, new \Twig_Environment());

        $authorPage = new Document();
        $generator->expects($this->once())
            ->method('generateAuthorIndices')
            ->with($author)
            ->will($this->returnValue(array($authorPage)));

        $event = new CarewEvent();
        $subscriber->onAuthors($event);

        $this->assertSame(array($authorPage), $event->getSubject());
    }

    public function testOnAuthorSetsAuthorsInTwigEnvironment()
    {
        $author = new Author('test');
        $authors = new AuthorRegistry();
        $authors->addAuthor($author);
        $generator = $this->getMock('Carew\Plugin\Authors\Generator\AuthorIndexGenerator', array(), array(), '', false);
        $generator->expects($this->any())
            ->method('generateAuthorIndices')
            ->will($this->returnValue(array()));

        $twig = $this->getMock('Twig_Environment');

        $subscriber = new AuthorsEventSubscriber($authors, $generator, $twig);

        $twig->expects($this->once())
            ->method('addGlobal')
            ->with('authors', array($author));

        $subscriber->onAuthors(new CarewEvent());
    }
}
