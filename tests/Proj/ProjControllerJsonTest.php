<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjControllerJsonTest extends WebTestCase
{
    public function testApiHome(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/api');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'API routes för projektet');
    }

    public function testGetAllItemsFromJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/api/items');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testGetAllButtonsFromJson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/api/buttons');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
    }
}