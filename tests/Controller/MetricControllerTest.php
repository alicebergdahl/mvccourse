<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MetricControllerTest extends WebTestCase
{
    public function testMetricRoute()
    {
        $client = static::createClient();

        $client->request('GET', '/metric');

        $this->assertSame(200, $client->getResponse()->getStatusCode());
    }

    public function testMetricTemplateRendering()
    {
        $client = static::createClient();

        $client->request('GET', '/metric');

        $this->assertStringContainsString(
            'Mätningar och Analys av Kodkvalitet',
            $client->getResponse()->getContent()
        );
    }
}