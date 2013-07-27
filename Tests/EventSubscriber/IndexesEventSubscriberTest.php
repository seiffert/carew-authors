<?php

namespace Carew\Plugin\Authors\Tests\EventSubscriber;

use Carew\Document;
use Carew\Event\CarewEvent;
use Carew\Event\Events;
use Carew\Plugin\Authors\AuthorRegistry;
use Carew\Plugin\Authors\Entity\Author;
use Carew\Plugin\Authors\Events as AuthorEvents;
use Carew\Plugin\Authors\EventSubscriber\IndexesEventSubscriber;

/**
 * @group unit
 */
class IndexEventSubscriberTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @return array|string[][]
     */
    public function getContentTypes()
    {
        return array(array('pages'), array('posts'));
    }

    public function testSubscribesIndexEvent()
    {
        $this->assertArrayHasKey(Events::INDEXES, IndexesEventSubscriber::getSubscribedEvents());
    }

    /**
     * @dataProvider getContentTypes
     *
     * @param string $contentType
     */
    public function testOnIndexesAddsDocumentsToAuthors($contentType)
    {
        $author = new Author('seiffert');

        $authors = new AuthorRegistry();
        $authors->addAuthor($author);

        $subscriber = new IndexesEventSubscriber($authors);
        $document = new Document();
        $document->setMetadatas(array('author' => 'seiffert'));

        $index = new Document();
        $index->setMetadatas(array($contentType => array($document)));

        $subscriber->onIndexes($this->createEvent(array($index)));

        $this->assertSame(array($document), $author->getDocuments());
    }

    public function testOnIndexDispatchesAuthorEvent()
    {
        $subscriber = new IndexesEventSubscriber(new AuthorRegistry());

        $event = $this->createEvent();
        $event->getDispatcher()
            ->expects($this->once())
            ->method('dispatch')
            ->with(AuthorEvents::AUTHORS);

        $subscriber->onIndexes($event);
    }

    /**
     * @param Document $subject
     * @return CarewEvent
     */
    private function createEvent($subject = null)
    {
        if (null === $subject) {
            $subject = array(new Document());
        }

        $dispatcher = $this->getMock('Symfony\Component\EventDispatcher\EventDispatcher');

        $event = new CarewEvent($subject);
        $event->setDispatcher($dispatcher);

        return $event;
    }
}
