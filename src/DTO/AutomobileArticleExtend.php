<?php

declare(strict_types=1);

namespace App\DTO;

class AutomobileArticleExtend extends AutomobileArticle
{
    public string $section;
    public string $subsection;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->setSection($data);
        $this->setSubsection($data);
    }

    protected function setSection(array $data): void
    {
        $this->section = (string)($data['section_name'] ?? '');
    }

    protected function setSubsection(array $data): void
    {
        $this->subsection = (string)($data['subsection_name'] ?? '');
    }
}
