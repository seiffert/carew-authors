<?php

namespace Carew\Plugin\Authors\Tests\Entity;

use Carew\Document;
use Carew\Plugin\Authors\Entity\Author;

class AuthorTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiation()
    {
        $handle = 'test';
        $author = new Author($handle);

        $this->assertSame($handle, $author->getHandle());
    }

    public function testGetProperty()
    {
        $testProperty = 'my-property';
        $testValue = 'property-value';
        $author = new Author('test', array($testProperty => $testValue));

        $this->assertSame($testValue, $author->get($testProperty));
    }

    public function testGetUnknownProperty()
    {
        $author = new Author('test');

        $this->assertNull($author->get('invalid-attribute'));
    }

    public function testGetDocumentsEmpty()
    {
        $author = new Author('test');

        $this->assertSame(array(), $author->getDocuments());
    }

    public function testAddDocument()
    {
        $document = new Document();
        $author = new Author('test');

        $author->addDocument($document);

        $this->assertSame(array($document), $author->getDocuments());
    }
}
