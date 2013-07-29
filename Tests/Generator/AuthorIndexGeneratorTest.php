<?php

namespace Carew\Plugin\Authors\Tests\Generator;

use Carew\Plugin\Authors\Entity\Author;
use Carew\Plugin\Authors\Generator\AuthorIndexGenerator;
use org\bovigo\vfs\vfsStream;
use Symfony\Component\Finder\Finder;

class AuthorIndexGeneratorTest extends \PHPUnit_Framework_TestCase
{
    public function testGeneratedDocumentTitle()
    {
        $generator = new AuthorIndexGenerator($this->getBaseDirWithLayout(), new Finder());
        $author = new Author('test', array('name' => 'Tester Test'));

        $indexes = $generator->generateAuthorIndices($author);
        $index = $indexes[0];
        $this->assertSame('Author: Tester Test (test)', $index->getTitle());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRequiresLayout()
    {
        $generator = new AuthorIndexGenerator($this->getBaseDir(), new Finder());
        $author = new Author('test');

        $generator->generateAuthorIndices($author);
    }

    public function testGeneratedDocumentLayout()
    {
        $baseDir = $this->getBaseDirWithLayout();

        $generator = new AuthorIndexGenerator($baseDir, new Finder());
        $author = new Author('test');

        $indexes = $generator->generateAuthorIndices($author);

        $index = $indexes[0];
        $this->assertSame('authors.html.twig', $index->getLayout());
    }

    public function testGeneratedDocumentVars()
    {
        $baseDir = $this->getBaseDirWithLayout();

        $generator = new AuthorIndexGenerator($baseDir, new Finder());
        $author = new Author('test');

        $indexes = $generator->generateAuthorIndices($author);

        $index = $indexes[0];
        $this->assertSame(array('author' => $author), $index->getVars());
    }

    public function testGeneratedDocumentPath()
    {
        $baseDir = $this->getBaseDirWithLayout();

        $generator = new AuthorIndexGenerator($baseDir, new Finder());
        $author = new Author('test');

        $indexes = $generator->generateAuthorIndices($author);

        $index = $indexes[0];
        $this->assertSame('authors/test.html', $index->getPath());
    }

    /**
     * @return string
     */
    private function getBaseDirWithLayout()
    {
        $baseDir = vfsStream::setup('base-dir');
        vfsStream::create(array(
            'layouts' => array(
                'authors.html.twig' => '{{ author.handle }}'
            ),
            $baseDir
        ));

        return vfsStream::url('base-dir');
    }

    /**
     * @return string
     */
    private function getBaseDir()
    {
        vfsStream::setup('base-dir');

        return vfsStream::url('base-dir');
    }
}
