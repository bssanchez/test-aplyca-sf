<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    private $url = '/contact-us';

    /**
     * @description Check route is OK 
     */
    public function testIndexResponseOK()
    {
        $client = static::createClient();
        $client->request('GET', $this->url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @description Check if title is 'BLOG'
     */
    public function testIndexTitle()
    {
        $client = static::createClient();
        $client->request('GET', $this->url);

        $this->assertSelectorTextSame('html h1', 'CONTÁCTANOS');
    }
}
