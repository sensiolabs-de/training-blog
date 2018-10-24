<?php

declare(strict_types = 1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful(string $url): void
    {
        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
    }

    public function urlProvider(): array
    {
        return [
            ['/en/'],
            ['/de/'],
            ['/es/'],
        ];
    }

    public function testFrontpageRedirect(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertSame('http://localhost/en', $client->getResponse()->headers->get('Location'));
    }
}
