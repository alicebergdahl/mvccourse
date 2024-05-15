<?php

namespace App\Tests\Entity;

use App\Entity\Book;
use PHPUnit\Framework\TestCase;

class BookRepositoryTest extends TestCase
{
    public function testBookInstance(): void
    {
        $book = new Book();

        $this->assertInstanceOf(Book::class, $book);
    }

    public function testNameGetterAndSetter(): void
    {
        $book = new Book();

        $book->setName('Test Book');
        $this->assertEquals('Test Book', $book->getName());
    }

    public function testAuthorGetterAndSetter(): void
    {
        $book = new Book();

        $book->setAuthor('Test Author');
        $this->assertEquals('Test Author', $book->getAuthor());
    }

    public function testISBNGetterAndSetter(): void
    {
        $book = new Book();

        $book->setISBN(1234567890);
        $this->assertEquals(1234567890, $book->getISBN());
    }

    public function testImageGetterAndSetter(): void
    {
        $book = new Book();

        $book->setImage('test_image.png');
        $this->assertEquals('test_image.png', $book->getImage());
    }
}