<?php

namespace Carew\Plugin\Authors\Tests;

use Carew\Plugin\Authors\AuthorRegistry;
use Carew\Plugin\Authors\Entity\Author;

class AuthorRegistryTest extends \PHPUnit_Framework_TestCase
{
    /** @var AuthorRegistry */
    private $registry;

    public function setUp()
    {
        $this->registry = new AuthorRegistry();
    }

    public function testEmpty()
    {
        $this->assertSame(array(), $this->registry->getAuthors());
    }

    public function testAddAuthor()
    {
        $handle = 'test';

        $author = new Author($handle);

        $this->registry->addAuthor($author);

        $this->assertSame($author, $this->registry->getAuthor($handle));
        $this->assertSame(array($author), $this->registry->getAuthors());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testGetUnkownAuthor()
    {
        $this->registry->getAuthor('test');
    }
}
