<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LibraryControllerTest extends WebTestCase
{
    public function testListBooks(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library');
        $this->assertResponseIsSuccessful();
        // Add more assertions to verify the presence of expected content
    }

    public function testAddBook(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/library/add');

        $this->assertResponseIsSuccessful();

        // Add assertions to test form submission
        $form = $crawler->selectButton('Lägg till bok')->form();
        $client->submit($form);

        // Add assertions to verify redirection and book addition
    }

    public function testViewBook(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library/book/14'); // Assuming book with ID 1 exists
        $this->assertResponseIsSuccessful();
        // Add more assertions to verify the presence of expected content
    }

    public function testEditBook(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/library/edit/14'); // Assuming book with ID 1 exists
        $this->assertResponseIsSuccessful();

        // Add assertions to test form submission for editing
        $form = $crawler->selectButton('Lägg till bok')->form();
        $client->submit($form);

        // Add assertions to verify redirection and book editing
    }
}