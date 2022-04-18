<?php

declare(strict_types=1);

namespace App\DTO;

use JetBrains\PhpStorm\Pure;

abstract class DTO implements \JsonSerializable
{
    #[Pure]
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
