<?php

declare(strict_types=1);

namespace App\Parsers;

use App\Contracts\Parser;
use InvalidArgumentException;

class PDFParser implements Parser
{

    public function __construct()
    {
    }

    public function read(string $file): string
    {
        throw new InvalidArgumentException("PDF Parser not implemented yet.");
    }

    public function parse(string $content): array
    {
        throw new InvalidArgumentException("PDF Parser not implemented yet.");
    }
}
