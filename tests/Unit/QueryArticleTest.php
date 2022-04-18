<?php

namespace App\Tests\Unit;

use App\DTO\QueryArticle;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QueryArticleTest extends TestCase
{
    #[ArrayShape(['set 1 - no API Key' => "array[]", 'set 2 - empty API Key' => "array[]", 'set 3 - incorrect sort method' => "\string[][]"])]
    public function provideIncorrectData(): array
    {
        return [
            'set 1 - no API Key' => [
                'data' =>
                    [
                        'sort' => QueryArticle::NEWEST,
                        'newsDesk' => 'Test',
                        'fieldList' => 'headline,pub_date',
                    ]
            ],
            'set 2 - empty API Key' => [
                'data' =>
                    [
                        'sort' => QueryArticle::NEWEST,
                        'newsDesk' => 'Test',
                        'fieldList' => 'headline,pub_date',
                        'apiKey' => ''
                    ]
            ],
            'set 3 - incorrect sort method' => [
                'data' =>
                    [
                        'sort' => 'test',
                        'newsDesk' => 'Test',
                        'fieldList' => 'headline,pub_date',
                        'apiKey' => 'key'
                    ]
            ],

        ];
    }

    /**
     * @dataProvider provideIncorrectData
     */
    public function testIncorrectData(array $data): void
    {
        $validator = $this->getValidator();

        $queryArticle = new QueryArticle($data);

        $violations = $validator->validate($queryArticle);
        self::assertCount(1, $violations);
    }

    #[ArrayShape(['set 1' => "array[]", 'set 2' => "array[]", 'set 3' => "array[]"])]
    public function provideCorrectData(): array
    {
        return [
            'set 1' => [
                'data' =>
                    [
                        'sort' => QueryArticle::OLDEST,
                        'newsDesk' => '',
                        'fieldList' => '',
                        'apiKey' => 'key'
                    ]
            ],
            'set 2' => [
                'data' =>
                    [
                        'sort' => QueryArticle::RELEVANT,
                        'newsDesk' => 'Test',
                        'apiKey' => 'key'
                    ]
            ],
            'set 3' => [
                'data' =>
                    [
                        'sort' => QueryArticle::NEWEST,
                        'newsDesk' => '',
                        'fieldList' => 'headline',
                        'apiKey' => '123'
                    ]
            ],

        ];
    }

    /**
     * @dataProvider provideCorrectData
     */
    public function testCorrectData(array $data): void
    {
        $validator = $this->getValidator();

        $queryArticle = new QueryArticle($data);

        $violations = $validator->validate($queryArticle);
        self::assertCount(0, $violations);
    }

    /**
     * @dataProvider provideCorrectData
     */
    public function testConvertToArray(array $data): void
    {
        $queryArticle = new QueryArticle($data);
        $queryArticleArray = $queryArticle->toArray();

        self::assertIsArray($queryArticleArray);

        self::assertArrayHasKey('sort', $queryArticleArray);
        self::assertArrayHasKey('fq', $queryArticleArray);
        self::assertArrayHasKey('fl', $queryArticleArray);
        self::assertArrayHasKey('q', $queryArticleArray);
        self::assertArrayHasKey('api-key', $queryArticleArray);
    }

    private function getValidator(): ValidatorInterface
    {
        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator();
    }


}
