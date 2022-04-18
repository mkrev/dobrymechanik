<?php

declare(strict_types=1);

namespace App\Action;

use App\DTO\AutomobileArticleExtend;

class GetAutomobileArticlesExtend extends GetArticles
{
    protected function getResponseData(array $content): array
    {
        return array_map(static fn(array $val) => new AutomobileArticleExtend($val), $content);
    }
}
