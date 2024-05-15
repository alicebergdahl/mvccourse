<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LibraryControllerTest extends WebTestCase
{
    public function testAddBook(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/library/add');

        $form = $crawler->selectButton('Lägg till bok')->form();

        $form['book[name]'] = 'Test Book';
        $form['book[author]'] = 'Test Author';
        $form['book[ISBN]'] = '1234567890';
        $form['book[image]'] = 'https://example.com/image.jpg';

        $client->submit($form);

        $this->assertResponseRedirects('/library');
    }

    public function testEditBook(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/library/edit/12'); // Assuming there is a book with id 1

        $form = $crawler->selectButton('Lägg till bok')->form();

        // Update book information
        $form['book[name]'] = 'Updated Test Book';
        $form['book[author]'] = 'Updated Test Author';
        $form['book[ISBN]'] = '0987654321';
        $form['book[image]'] = 'https://example.com/updated_image.jpg';

        $client->submit($form);

        $this->assertResponseRedirects('/library/book/12'); // Assuming redirection to view book page after editing
    }

    public function testViewBook(): void
    {
        $client = static::createClient();

        $client->request('GET', '/library/book/12'); // Assuming there is a book with id 1

        $this->assertResponseIsSuccessful();
    }
}