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
        $index->setVars(array($contentType => array($document)));

        $subscriber->onIndexes($this->createEvent(array($index)));

        $document->setMetadatas(array('author' => $author), true);
        $this->assertSame(array($document), $author->getDocuments());
    }

    public function testOnIndexDispatchesAuthorEvent()
    {
        $subscriber = new IndexesEventSubscriber(new AuthorRegistry());

        $event = $this->createEvent();
        $indexCount = count($event->getSubject());

        $authorsDocs = array(new Document());

        /** @var \PHPUnit_Framework_MockObject_MockObject $dispatcher */
        $dispatcher = $event->getDispatcher();
        $dispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(AuthorEvents::AUTHORS, $this->isInstanceOf('Carew\Event\CarewEvent'))
            ->will($this->returnCallback(function ($eventType, CarewEvent $event) use ($authorsDocs) {
                $event->setSubject($authorsDocs);
            }));

        $subscriber->onIndexes($event);

        $this->assertCount(count($authorsDocs) + $indexCount, $event->getSubject());
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
