<?php

declare(strict_types=1);

namespace App\Action;

use App\DTO\QueryArticle;
use App\Exception\ApiException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class GetArticles
{
    private HttpClientInterface $client;
    private QueryArticle $query;

    public const URL = 'https://api.nytimes.com/svc/search/v2/articlesearch.json';

    public function __construct(HttpClientInterface $client, QueryArticle $query)
    {
        $this->client = $client;
        $this->query = $query;
    }

    public function handle(): array
    {
        try {
            $response = $this->client->request(
                Request::METHOD_GET,
                self::URL, [
                'query' => $this->query->toArray()
            ]);

            $content = $response->toArray();
        } catch (\Throwable $e) {
            throw new ApiException('Nie udało się poprawnie pobrać danych z API');
        }

        return $this->getResponseData($content['response']['docs']);
    }

    abstract protected function getResponseData(array $content): array;
}
