<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BlogControllerTest extends WebTestCase
{
    private $url = '/blog';

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

        $this->assertSelectorTextSame('html h1', 'BLOG');
    }

    /**
     * @description Check if exists posts in posts section
     */
    public function testIndexLastPost()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', $this->url);

        $posts = $crawler
            ->filter('.post-list .post__item')
            ->count();

        $this->assertGreaterThan(0, $posts);
    }
}
