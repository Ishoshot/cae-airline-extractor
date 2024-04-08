<?php

declare(strict_types=1);

namespace App\Contracts;

interface Parser
{
    public function read(string $file): string;

    public function parse(string $content): array;
}
