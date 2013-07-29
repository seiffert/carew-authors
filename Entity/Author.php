<?php

namespace Carew\Plugin\Authors\Entity;

use Carew\Document;

class Author
{
    /** @var string */
    private $handle;

    /** @var array */
    private $attributes = array(
        'name' => 'unknown',
        'email' => ''
    );

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
     * @param string $attribute
     * @return mixed
     */
    public function get($attribute)
    {
        $value = null;

        if (isset($this->attributes[$attribute])) {
            $value = $this->attributes[$attribute];
        }

        return $value;
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
