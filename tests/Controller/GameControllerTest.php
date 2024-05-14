<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game');
        $client->catchExceptions(false);
        $this->assertResponseIsSuccessful();
    }

    public function testStartGame(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/start');
        $client->catchExceptions(false);
        $this->assertResponseIsSuccessful();
    }

    public function testDrawCard(): void
    {
        $client = static::createClient();
        $client->request('POST', '/game/draw');
        $client->catchExceptions(false);
        $this->assertResponseIsSuccessful();
    }

    public function testDocumentation(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/doc');
        $client->catchExceptions(false);
        $this->assertResponseIsSuccessful();
    }

    public function testStandGame(): void
    {
        $client = static::createClient();
        $client->request('POST', '/game/stand');
        $client->catchExceptions(false);
        $this->assertResponseIsSuccessful();
    }

    public function testGetGameState(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/game');
        $client->catchExceptions(false);
        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');
    }

    public function testDrawCardGameOver(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/start');
        $client->catchExceptions(false);
        for ($i = 0; $i < 10; $i++) {
            $client->request('POST', '/game/draw');
        }
        $crawler = $client->getCrawler();
        
        $gameOverText = $crawler->filter('.game-over-message')->text();
        $this->assertStringContainsString('Game over', $gameOverText);
    }

    public function testStandGameBankWins(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/start');
        $client->request('POST', '/game/draw');
        $client->request('POST', '/game/draw');
        $client->request('POST', '/game/stand');
        $client->catchExceptions(false);
        $crawler = $client->getCrawler();

        $gameOverText = $crawler->filter('.game-over-message')->text();
        $this->assertStringContainsString('Game over', $gameOverText);
    }

    public function testGetGameStateWithAllConditions(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/start');
        $client->request('POST', '/game/draw');
        $client->request('POST', '/game/draw');
        $client->request('POST', '/game/draw');
        $response = $client->request('GET', '/api/game');
        $client->catchExceptions(false);
        $content = $client->getResponse()->getContent();

        if (!is_string($content)) {
            $this->fail('Expected a string response body');
        }

        $decodedContent = json_decode($content, true);

        if (!is_array($decodedContent)) {
            $this->fail('JSON response could not be decoded or is not an array');
        }

        $this->assertArrayHasKey('game_over', $decodedContent);
        $this->assertArrayHasKey('win_message', $decodedContent);
    }

    public function testPlayerWinsByBank(): void
    {
        $client = static::createClient();
        $client->request('GET', '/game/start');
        $client->request('POST', '/game/draw');
        $client->request('POST', '/game/draw');
        $client->request('POST', '/game/stand');
        $client->catchExceptions(false);
        $crawler = $client->getCrawler();
        $this->assertTrue(
            $crawler->filter('.game-over-message, .win-message')->count() > 0,
            'No game-over or win message found.'
        );
    }
}
