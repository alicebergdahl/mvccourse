<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CardControllerTest extends WebTestCase
{
    public function testGetSortedDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $content = $client->getResponse()->getContent();
        
        if (!is_string($content)) {
            $this->fail('Expected a string response body');
        }

        $decodedResponse = json_decode($content, true);

        if (!is_array($decodedResponse) || !isset($decodedResponse[0]['suit'], $decodedResponse[0]['value'])) {
            $this->fail('Decoded response is not as expected');
        }

        $this->assertEquals('hearts', $decodedResponse[0]['suit']);
        $this->assertEquals('A', $decodedResponse[0]['value']);
    }

    public function testShuffleDeck(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck/shuffle');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $content = $client->getResponse()->getContent();
        
        if (!is_string($content)) {
            $this->fail('Expected a string response body');
        }

        $decodedResponse = json_decode($content, true);
        $this->assertIsArray($decodedResponse);
    }

    public function testDrawOneCard(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck/draw');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $content = $client->getResponse()->getContent();
        
        if (!is_string($content)) {
            $this->fail('Expected a string response body');
        }

        $decodedResponse = json_decode($content, true);

        if (!is_array($decodedResponse) || !isset($decodedResponse['drawnCards'], $decodedResponse['remainingCards'])) {
            $this->fail('Decoded response is not as expected');
        }

        $this->assertCount(1, $decodedResponse['drawnCards']);
        $this->assertLessThanOrEqual(51, $decodedResponse['remainingCards']);
    }

    public function testDrawCards(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/deck/draw/5');
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
        $content = $client->getResponse()->getContent();
        
        if (!is_string($content)) {
            $this->fail('Expected a string response body');
        }

        $decodedResponse = json_decode($content, true);

        if (!is_array($decodedResponse) || !isset($decodedResponse['drawnCards'], $decodedResponse['remainingCards'])) {
            $this->fail('Decoded response is not as expected');
        }

        $this->assertCount(5, $decodedResponse['drawnCards']);
        $this->assertLessThanOrEqual(47, $decodedResponse['remainingCards']);
    }

    public function testMapSuitWithInvalidSuit(): void
    {
        $client = static::createClient();
        $container = $client->getContainer();
        $controller = new \App\Controller\CardControllerJson();
        $reflection = new \ReflectionClass($controller);
        $method = $reflection->getMethod('mapSuit');
        $method->setAccessible(true);
        $result = $method->invokeArgs($controller, ['InvalidSuit']);
        $this->assertEquals('', $result);
    }
}