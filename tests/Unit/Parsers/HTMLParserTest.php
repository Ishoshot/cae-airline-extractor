<?php

namespace Tests\Unit\Parsers;

use App\Enums\EventTypeEnum;
use App\Parsers\HTMLParser;
use PHPUnit\Framework\TestCase;

class HTMLParserTest extends TestCase
{
    protected $htmlParser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->htmlParser = new HTMLParser();
    }

    public function test_parser_can_read_input_file()
    {
        $filePath = 'tests/Roster - CrewConnex.html';

        $expectedContent = file_get_contents($filePath);

        $content = $this->htmlParser->read($filePath);

        $this->assertSame($expectedContent, $content);
    }

    public function test_parser_can_parse_file_content_correctly()
    {
        $content = file_get_contents('tests/Roster - CrewConnex.html');

        $expectedEventsCount = 54; // Specific to the Input file used
        $expectedFirstEvent = [
            'type' => EventTypeEnum::CheckIn->value,
            'from' => 'KRP',
            'to' => 'CPH',
            'departure' => '2022-01-10 08:45:00',
            'arrival' => '2022-01-10 09:35:00',
            'meta' => [
                'hotel' => 'DO4'
            ]
        ]; // Specific to the Input file used

        $events = $this->htmlParser->parse($content);

        $this->assertCount($expectedEventsCount, $events);
        $this->assertEventEquals($expectedFirstEvent, $events[0]);
    }

    protected function assertEventEquals(array $expectedEvent, array $actualEvent)
    {
        $this->assertSame($expectedEvent['type'], $actualEvent['type']);
        $this->assertSame($expectedEvent['from'], $actualEvent['from']);
        $this->assertSame($expectedEvent['to'], $actualEvent['to']);
        $this->assertSame($expectedEvent['departure'], $actualEvent['departure']);
        $this->assertSame($expectedEvent['arrival'], $actualEvent['arrival']);
        $this->assertSame($expectedEvent['meta'], $actualEvent['meta']);
    }
}
