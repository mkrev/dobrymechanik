<?php

namespace App\Tests\Unit;

use App\DTO\AutomobileArticle;
use JetBrains\PhpStorm\ArrayShape;
use PHPUnit\Framework\TestCase;

class AutomobileArticleTest extends TestCase
{
    #[ArrayShape(['set 1' => "\array[][]"])]
    public function provideMultimediaWithSearchImageSubtypeData(): array
    {
        return [
            'set 1' => [
                'data' =>
                    [
                        'multimedia' => [
                            [
                                'url' => 'Test1',
                                'type' => 'Test1',
                                'caption' => 'Test1',
                                'subtype' => AutomobileArticle::SEARCH_IMAGE_SUBTYPE
                            ],
                            [
                                'url' => 'Test2',
                                'type' => 'Test2',
                                'caption' => 'Test2',
                                'subtype' => 'cocoJamboo'
                            ],
                            [
                                'url' => 'Test3',
                                'type' => 'Test3',
                                'caption' => 'Test3',
                                'subtype' => ''
                            ],

                        ],
                    ]
            ],
        ];
    }

    /**
     * @dataProvider provideMultimediaWithSearchImageSubtypeData
     */
    public function testCorrectSearchImage(array $data): void
    {
        $queryArticle = new AutomobileArticle($data);
        $image = $queryArticle->getImage();

        self::assertEquals(AutomobileArticle::SEARCH_IMAGE_SUBTYPE, $image['subtype']);
    }

    #[ArrayShape(['set 1' => "\string[][][][]", 'set 2' => "\array[][]"])]
    public function provideMultimediaWithoutSearchImageSubtypeData(): array
    {
        return [
            'set 1' => [
                'data' =>
                    [
                        'multimedia' => [
                            [
                                'url' => 'Test1',
                                'type' => 'Test1',
                                'caption' => 'Test1',
                                'subtype' => 'Test1'
                            ],
                            [
                                'url' => 'Test2',
                                'type' => 'Test2',
                                'caption' => 'Test2',
                                'subtype' => 'cocoJamboo'
                            ],
                            [
                                'url' => 'Test3',
                                'type' => 'Test3',
                                'caption' => 'Test3',
                                'subtype' => ''
                            ],

                        ],
                    ]
            ],
            'set 2' => [
                'data' =>
                    [
                        'multimedia' => [],
                    ]
            ],
        ];
    }

    /**
     * @dataProvider provideMultimediaWithoutSearchImageSubtypeData
     */
    public function testCorrectNoSearchImage(array $data): void
    {
        $queryArticle = new AutomobileArticle($data);

        self::assertEmpty($queryArticle->getImage());
    }
}
