<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SessionDebugControllerTest extends WebTestCase
{
    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/session');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Session Debug');
        $this->assertSelectorExists('pre');
    }

    public function testDelete(): void
    {
        $client = static::createClient();
        $client->request('GET', '/session');
        $session = $client->getRequest()->getSession();
        $session->set('test', 'value');
        $this->assertTrue($session->has('test'), 'Session should have test data before deletion');
        $client->request('GET', '/session/delete');
        $this->assertResponseRedirects('/card');
        $client->followRedirect();
        $session = $client->getRequest()->getSession();
        $this->assertFalse($session->has('test'), 'Session should be empty after deletion');
        $crawler = $client->getCrawler();
        $this->assertNotEmpty($crawler->filter('.flash-success')->text(), 'Flash message is displayed');
    }
}
