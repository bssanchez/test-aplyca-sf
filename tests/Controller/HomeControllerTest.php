<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    /**
     * @description Check route is OK 
     */
    public function testIndexResponseOK()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    /**
     * @description Check if title contains 'BLOG'
     */
    public function testIndexTitle()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertSelectorTextContains('html h1', 'MY DUMMY BLOG');
    }

    /**
     * @description Check if exists 3 post in last posts
     */
    public function testIndexLastPost()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $posts = $crawler
            ->filter('.post__item')
            ->count();

        $this->assertGreaterThan(0, $posts);
    }

    /**
     * @description Check if link VER TODAS go to /blog page
     */
    public function testIndexShowMorePosts()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler
            ->filter('a:contains("TODAS")')
            ->eq(0)
            ->link();

        $crawler = $client->click($link);
        $this->assertSelectorTextSame('html h1', 'BLOG');
    }
}
