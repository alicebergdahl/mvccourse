<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProjControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Välkommen till spelet');
    }

    public function testAbout(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/about');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Om spelet');
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

    public function testStartGame(): void
    {
        $client = static::createClient();
        $crawler = $client->request('POST', '/proj/start', ['playerName' => 'Test Player']);
        $this->assertResponseRedirects('/proj/game/1');
        
        // You can add additional assertions here to verify the session state or database changes.
    }

    public function testMove(): void
    {
        $client = static::createClient();
        $client->request('GET', '/proj/move/north');
        $this->assertResponseRedirects('/proj/game/1');
        
        // You can add additional assertions here to verify the updated game state.
    }

    // Add more tests for other controller methods as needed
}