<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


class ProjControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Äventyrsspelet');
    }

    public function testAbout(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/about');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Om äventyrsspelet');
    }

    public function testAboutDatabase(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/about/database');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Databasen för projektet');
    }

    public function testDocumentation(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/docs');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Spel Dokumentation');
    }

    public function testMove(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/move/north');
        $this->assertResponseRedirects('/proj/game/1');
    }
}