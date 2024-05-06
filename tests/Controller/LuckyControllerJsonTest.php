<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LuckyControllerJsonTest extends WebTestCase
{
    public function testLandingPage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'API Landing Page'); // Assuming you have an <h1> tag in your template
    }

    public function testGetQuote(): void
    {
        $client = static::createClient();
        $client->request('GET', '/api/quote');
        $response = $client->getResponse();
        $this->assertResponseIsSuccessful();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $content = $response->getContent();
        if (!is_string($content)) {
            $this->fail('Expected a string response body');
        }
        $decodedContent = json_decode($content, true);
        if (!is_array($decodedContent)) {
            $this->fail('JSON response could not be decoded or is not an array');
        }
        $this->assertArrayHasKey('song', $decodedContent);
        $this->assertArrayHasKey('date', $decodedContent);
        $this->assertArrayHasKey('time', $decodedContent);
        if (isset($decodedContent['date'])) {
            $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2}/', $decodedContent['date']);
        }
        if (isset($decodedContent['time'])) {
            $this->assertMatchesRegularExpression('/\d{2}:\d{2}:\d{2}/', $decodedContent['time']);
        }
    }
}
