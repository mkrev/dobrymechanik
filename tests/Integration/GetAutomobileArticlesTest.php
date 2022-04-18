<?php

namespace App\Tests\Integration;

use App\Action\GetAutomobileArticles;
use App\DTO\QueryArticle;
use App\Exception\ApiException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;

class GetAutomobileArticlesTest extends TestCase
{
    private function createGetAutomobileArticles(QueryArticle $queryArticle): GetAutomobileArticles
    {
        $client = HttpClient::create();

        return new GetAutomobileArticles($client, $queryArticle);
    }

    public function testCorrectAction(): void
    {
        $queryArticle = new QueryArticle([
            'sort' => QueryArticle::NEWEST,
            'newsDesk' => 'Automobiles',
            'fieldList' => 'headline,pub_date,lead_paragraph,multimedia,web_url,section_name,subsection_name',
            'apiKey' => $_ENV['NYTIMES_API_KEY']
        ]);

        $action = $this->createGetAutomobileArticles($queryArticle);
        $response = $action->handle();

        self::assertIsArray($response);
        self::assertCount(10, $response);
    }

    public function testActionWithIncorrectApiKey(): void
    {
        $this->expectException(ApiException::class);

        $queryArticle = new QueryArticle([
            'sort' => QueryArticle::NEWEST,
            'newsDesk' => 'Automobiles',
            'fieldList' => 'headline,pub_date,lead_paragraph,multimedia,web_url,section_name,subsection_name',
            'apiKey' => 'key'
        ]);

        $action = $this->createGetAutomobileArticles($queryArticle);
        $action->handle();
    }
}
