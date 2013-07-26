<?php

namespace Carew\Plugin\Authors;

use Carew\Document;
use Carew\Event\CarewEvent;
use Carew\Event\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AuthorsEventSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(Events::INDEXES => 'onIndexes');
    }

    /**
     * @param CarewEvent $event
     */
    public function onIndexes(CarewEvent $event)
    {
        /** @var Document[] $documents */
        $documents = $event->getSubject();

        $authors = array();
        foreach ($documents as $doc) {
            if (false !== strpos($doc->getPath(), 'html')) {
                $vars = $doc->getVars();

                $authors = array_merge(
                    $authors,
                    $this->getAuthors($vars['posts']),
                    $this->getAuthors($vars['pages'])
                );
            }
        }

        $authors = array_unique($authors);

        foreach ($authors as $author) {
            $documents[] = $this->generateAuthorDocument($author);
        }

        $event->setSubject($documents);
    }

    /**
     * @param array|Document[] $documents
     * @return array
     */
    private function getAuthors(array $documents)
    {
        $authors = array();

        foreach ($documents as $doc) {
            $metadata = $doc->getMetadatas();
            if (isset($metadata['author'])) {
                $authors[] = $metadata['author'];
            }
        }

        return $authors;
    }

    /**
     * @param string $author
     */
    private function generateAuthorDocument($author)
    {
        $document = new Document();

        // @TODO set layout
        //$document->setLayout((string) $file);
        // @TODO don't hardcode format
        $document->setPath(sprintf('authors/%s.%s', $author, 'html'));
        $document->setTitle('Author: ' . $author);
        $document->setVars(array(
            'author'   => $author
            // @TODO render posts of author
        ));

        // @TODO dispatch AUTHOR event

        return $document;
    }
}
