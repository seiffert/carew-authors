<?php

namespace Carew\Plugin\Authors;

use Carew\Plugin\Authors\Entity\Author;

class AuthorRegistry
{
    /** @var array|Author[] */
    private $authors = array();

    /**
     * @param Author $author
     */
    public function addAuthor(Author $author)
    {
        $this->authors[$author->getHandle()] = $author;
    }

    /**
     * @param string $handle
     * @return Author
     */
    public function getAuthor($handle)
    {
        return $this->authors[$handle];
    }
}
