<?php

namespace Carew\Plugin\Authors\Entity;

use Carew\Document;

class Author
{
    /** @var string */
    private $handle;

    /** @var array */
    private $attributes;

    /** @var array|Document[] */
    private $documents = array();

    /**
     * @param string $handle
     * @param array $attributes
     */
    public function __construct($handle, array $attributes = array())
    {
        $this->handle = $handle;
        $this->attributes = $attributes;
    }

    /**
     * @return string
     */
    public function getHandle()
    {
        return $this->handle;
    }

    /**
     * @return array|Document[]
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * @param Document $doc
     */
    public function addDocument(Document $doc)
    {
        $this->documents[] = $doc;
    }
}
