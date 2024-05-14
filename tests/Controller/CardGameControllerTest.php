<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardGameControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card');
        $client->catchExceptions(false);
        $this->assertResponseIsSuccessful();
    }

    public function testShowDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck');
        $client->catchExceptions(false);
        $this->assertResponseIsSuccessful();
    }

    public function testShuffleDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/shuffle');
        $client->catchExceptions(false);
        $this->assertResponseIsSuccessful();
    }

    public function testDrawOneCard(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/draw');
        $client->catchExceptions(false);
        $this->assertResponseRedirects('/card/deck/draw/1');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testDrawCards(): void
    {
        $client = static::createClient();
        $client->request('GET', '/card/deck/draw/5');
        $client->catchExceptions(false);
        $this->assertResponseIsSuccessful();
    }

    public function testMapSuitWithInvalidSuit(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $container = static::getContainer();
        $controller = new \App\Controller\CardGameController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('mapSuit');
        $method->setAccessible(true);
        $result = $method->invokeArgs($controller, ['InvalidSuit']);
        $this->assertEquals('', $result);
    }

    public function testMapSuitDefaultCase(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $controller = new \App\Controller\CardGameController();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('mapSuit');
        $method->setAccessible(true);
        $result = $method->invokeArgs($controller, ['InvalidSuit']);
        $this->assertEmpty($result);
    }
}
