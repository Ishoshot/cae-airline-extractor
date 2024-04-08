<?php

namespace Tests\Unit\Factories;

use App\Factories\ParserFactory;
use App\Parsers\HTMLParser;
use App\Parsers\JSONParser;
use App\Parsers\PDFParser;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ParserFactoryTest extends TestCase
{
    public function test_make_returns_html_parser_instance_for_html_type()
    {
        $parser = ParserFactory::make('html');
        $this->assertInstanceOf(HTMLParser::class, $parser);
    }

    public function test_make_returns_json_parser_instance_for_json_type()
    {
        $parser = ParserFactory::make('json');
        $this->assertInstanceOf(JSONParser::class, $parser);
    }

    public function test_make_returns_pdf_parser_instance_for_pdf_type()
    {
        $parser = ParserFactory::make('pdf');
        $this->assertInstanceOf(PDFParser::class, $parser);
    }

    public function test_make_throws_invalid_argument_exception_for_invalid_type()
    {
        $this->expectException(InvalidArgumentException::class);
        ParserFactory::make('invalid');
    }
}
