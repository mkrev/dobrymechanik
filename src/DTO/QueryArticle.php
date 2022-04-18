<?php

declare(strict_types=1);

namespace App\DTO;

use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\Validator\Constraints as Assert;

class QueryArticle extends DTO
{
    public const NEWEST = 'newest';
    public const OLDEST = 'oldest';
    public const RELEVANT = 'relevant';

    public const ALLOWED_SORT_METHOD = [
        self::NEWEST,
        self::OLDEST,
        self::RELEVANT,
    ];

    #[Assert\Choice(choices: self::ALLOWED_SORT_METHOD, message: 'Choose a valid sort type.')]
    public string $sort;
    public string $newsDesk;
    public string $fieldList;
    public string $query;
    #[Assert\NotBlank(message: 'API key cannot be empty.')]
    public string $apiKey;


    public function __construct(array $data)
    {
        $this->sort = (string)($data['sort'] ?? self::NEWEST);
        $this->newsDesk = (string)($data['newsDesk'] ?? '');
        $this->fieldList = (string)($data['fieldList'] ?? '');
        $this->query = (string)($data['query'] ?? '');
        $this->apiKey = (string)($data['apiKey'] ?? '');
    }

    public function getSort(): string
    {
        return $this->sort;
    }

    public function getNewsDesk(): string
    {
        return $this->newsDesk;
    }

    public function getFieldList(): string
    {
        return $this->fieldList;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    #[ArrayShape(['sort' => "string", 'fq' => "string", 'fl' => "string", 'q' => "string", 'api-key' => "string"])]
    public function toArray(): array
    {
        return [
            'sort' => $this->getSort(),
            'fq' => 'news_desk:("' . $this->getNewsDesk() . '")',
            'fl' => $this->getFieldList(),
            'q' => $this->getQuery(),
            'api-key' => $this->getApiKey(),
        ];
    }
}
