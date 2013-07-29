<?php

namespace Carew\Plugin\Authors\Generator;

use Carew\Document;
use Carew\Plugin\Authors\Entity\Author;
use Symfony\Component\Finder\Finder;

class AuthorIndexGenerator
{
    /** @var Finder */
    private $finder;

    /** @var string */
    private $baseDir;

    /**
     * @param string $baseDir
     * @param Finder $finder
     */
    public function __construct($baseDir, Finder $finder)
    {
        $this->baseDir = $baseDir;
        $this->finder = $finder;
    }

    /**
     * @param Author $author
     * @return Document[]
     */
    public function generateAuthorIndices(Author $author)
    {
        $indexes = array();

        foreach ($this->getLayouts() as $layout) {
            $file = $layout->getBasename();

            preg_match('#authors\.(.+?)\.twig$#', $file, $match);

            $format = $match[1];

            $document = new Document();

            $document->setLayout((string) $file);
            $document->setPath(sprintf('authors/%s.%s', $author->getHandle(), $format));
            $document->setTitle(sprintf('Author: %s (%s)', $author->get('name'), $author->getHandle()));
            $document->setVars(array('author' => $author));

            $indexes[] = $document;
        }

        return $indexes;
    }

    /**
     * @return array
     * @throws \InvalidArgumentException
     */
    private function getLayouts()
    {
        $layouts = new \ArrayIterator(array());
        if (is_dir($this->baseDir . '/layouts')) {
            $layouts = $this->finder
                ->in($this->baseDir . '/layouts/')
                ->files()
                ->name('authors.*.twig');
        }

        $layouts = iterator_to_array($layouts);

        if (empty($layouts)) {
            throw new \InvalidArgumentException('Could not find layout for author pages.');
        }

        return $layouts;
    }
}
