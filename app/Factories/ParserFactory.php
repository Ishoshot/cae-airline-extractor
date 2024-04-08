<?php

declare(strict_types=1);

namespace App\Factories;

use App\Contracts\Parser;
use App\Parsers\HTMLParser;
use App\Parsers\JSONParser;
use App\Parsers\PDFParser;

class ParserFactory
{
    public static function make(string $type): Parser
    {
        return match ($type) {
            'html' => new HTMLParser(),
            'json' => new JSONParser(),
            'pdf' => new PDFParser(),
            default => throw new \InvalidArgumentException('Invalid parser type'),
        };
    }
}
