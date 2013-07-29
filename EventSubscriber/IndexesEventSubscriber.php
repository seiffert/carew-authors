<?php

namespace Carew\Plugin\Authors\EventSubscriber;

use Carew\Document;
use Carew\Event\CarewEvent;
use Carew\Event\Events;
use Carew\Plugin\Authors\Events as AuthorEvents;
use Carew\Plugin\Authors\AuthorRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class IndexesEventSubscriber implements EventSubscriberInterface
{
    /** @var AuthorRegistry */
    private $authors;

    /**
     * @param AuthorRegistry $registry
     */
    public function __construct(AuthorRegistry $registry)
    {
        $this->authors = $registry;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            Events::INDEXES => 'onIndexes'
        );
    }

    /**
     * @param CarewEvent $event
     */
    public function onIndexes(CarewEvent $event)
    {
        /** @var array|Document[] $index */
        $indexes = $event->getSubject();
        $docs = $this->getDocumentsFromIndexes($indexes);

        foreach ($docs as $doc) {
            $data = $doc->getMetadatas();
            $author = $this->authors->getAuthor($data['author']);

            $author->addDocument($doc);
        }

        $authorsEvent = new CarewEvent(array());
        $event->getDispatcher()->dispatch(AuthorEvents::AUTHORS, $authorsEvent);

        $event->setSubject(array_merge($indexes, $authorsEvent->getSubject()));
    }

    /**
     * @param array|Document[] $indexes
     * @return array|Document[]
     */
    private function getDocumentsFromIndexes(array $indexes)
    {
        $result = array();

        foreach ($indexes as $index) {
            $metadata = $index->getMetadatas();

            foreach (array('pages', 'posts') as $type) {
                if (isset($metadata[$type])) {
                    $result = array_merge($result, $metadata[$type]);
                }
            }
        }

        return $result;
    }
}
