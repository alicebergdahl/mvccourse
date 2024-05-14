<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardGameControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', '/card');
        $this->assertResponseIsSuccessful();
    }

    public function testShowDeck(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', '/card/deck');
        $this->assertResponseIsSuccessful();
    }

    public function testShuffleDeck(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', '/card/deck/shuffle');
        $this->assertResponseIsSuccessful();
    }

    public function testDrawOneCard(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', '/card/deck/draw');
        $this->assertResponseRedirects('/card/deck/draw/1');
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
    }

    public function testDrawCards(): void
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        $client->request('GET', '/card/deck/draw/5');
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
