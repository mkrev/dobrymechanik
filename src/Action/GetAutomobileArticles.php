<?php

declare(strict_types=1);

namespace App\Action;

use App\DTO\AutomobileArticle;

class GetAutomobileArticles extends GetArticles
{
    protected function getResponseData(array $content): array
    {
        return array_map(static fn(array $val) => new AutomobileArticle($val), $content);
    }
}
