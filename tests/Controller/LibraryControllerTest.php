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
    }

    public function testSeedBooks(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library/seed');

        $this->assertResponseIsSuccessful();
    }

    public function testAddBook(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library/add');

        $this->assertResponseIsSuccessful();
    }

    public function testViewBook(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library/book/14');

        $this->assertResponseIsSuccessful();
    }

    public function testEditBook(): void
    {
        $client = static::createClient();
        $client->request('GET', '/library/edit/14');

        $this->assertResponseIsSuccessful();
    }
}
