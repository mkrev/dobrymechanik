<?php

declare(strict_types=1);

namespace App\DTO;

use Doctrine\Common\Collections\ArrayCollection;

class AutomobileArticle extends DTO
{
    protected string $title;
    protected string $publicationDate;
    protected string $lead;
    protected array $image;
    protected string $url;

    public const SEARCH_IMAGE_SUBTYPE = 'superJumbo';

    public function __construct(array $data)
    {
        $this->setTitle($data);
        $this->setPublicationDate($data);
        $this->setLead($data);
        $this->setUrl($data);
        $this->setImage($data);
    }

    protected function setTitle(array $data): void
    {
        $this->title = (string)($data['headline']['main'] ?? '');
    }

    protected function setPublicationDate(array $data): void
    {
        $this->publicationDate = (string)($data['pub_date'] ?? '');
    }

    protected function setLead(array $data): void
    {
        $this->lead = (string)($data['lead_paragraph'] ?? '');
    }

    protected function setUrl(array $data): void
    {
        $this->url = (string)($data['web_url'] ?? '');
    }

    protected function setImage(array $data): void
    {
        $multimedia = new ArrayCollection($data['multimedia'] ?? []);
        $image = $multimedia->filter(fn($val) => $val['subtype'] === self::SEARCH_IMAGE_SUBTYPE)->first();

        $this->image = $image ? (array)$image : [];
    }

    public function getImage(): array
    {
        return $this->image;
    }
}
