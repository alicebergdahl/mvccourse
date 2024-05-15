<?php

namespace App\Tests\Form;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Test\TypeTestCase;
use App\Form\BookType;
use App\Entity\Book;

class BookTypeTest extends TypeTestCase
{
    public function testBuildForm(): void
    {
        $form = $this->factory->create(BookType::class);

        $this->assertTrue($form->has('name'));
        $this->assertTrue($form->has('author'));
        $this->assertTrue($form->has('ISBN'));
        $this->assertTrue($form->has('image'));
        $this->assertTrue($form->has('save'));
    }

    public function testSubmitValidData(): void
    {
        $book = new Book();

        $form = $this->factory->create(BookType::class, $book);

        $formData = [
            'name' => 'Test Book',
            'author' => 'Test Author',
            'ISBN' => 1234567890,
            'image' => 'test_image.png',
            'save' => true,
        ];

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($book, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}