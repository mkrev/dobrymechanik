<?php

namespace App\Tests\EndToEnd;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ArticleTest extends WebTestCase
{
    public function testGetArticlesList(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, 'nytimes/');
        $response = $client->getResponse();

        self::assertResponseIsSuccessful();
        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));
        self::assertJson($response->getContent());
    }

    public function testGetArticlesFilteredListWithoutAuth(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET,
            'nytimes/BMW',
        );
        $response = $client->getResponse();

        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));
        self::assertJson($response->getContent());
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
    }

    public function testGetArticlesFilteredList(): void
    {
        $client = static::createClient([], ['HTTP_AUTHORIZATION' => $_ENV['API_KEY']]);
        $client->request(Request::METHOD_GET,
            'nytimes/BMW',
          );
        $response = $client->getResponse();

        self::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        self::assertTrue($response->headers->contains('Content-Type', 'application/json'));
        self::assertJson($response->getContent());
    }
}
