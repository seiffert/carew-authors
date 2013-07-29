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
     * @throws \InvalidArgumentException
     * @return Author
     */
    public function getAuthor($handle)
    {
        if (!isset($this->authors[$handle])) {
            throw new \InvalidArgumentException('There is no author with handle "' . $handle . '" configured.');
        }

        return $this->authors[$handle];
    }

    /**
     * @return array|Author[]
     */
    public function getAuthors()
    {
        return array_values($this->authors);
    }
}
