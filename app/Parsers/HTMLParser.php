<?php

declare(strict_types=1);


namespace App\Parsers;

use App\Contracts\Parser;
use App\Enums\EventTypeEnum;
use Carbon\Carbon;
use Symfony\Component\DomCrawler\Crawler;

class HTMLParser implements Parser
{
    protected Crawler $crawler;

    protected Carbon $startingPeriod;

    public function __construct()
    {
        $this->crawler = new Crawler();
    }


    public function read(string $file): string
    {
        return file_get_contents($file);
    }


    public function parse(string $content): array
    {
        $this->crawler->addHtmlContent($content);

        $headers = [];

        $periodText = $this->crawler->filter('div.row.printOnly')->text();
        $periodText = explode(':', $periodText);
        $periodText = trim($periodText[1]);
        $periodText = substr($periodText, 0, 7);

        $this->startingPeriod = new Carbon($periodText, 'UTC');

        $activitiesRows = $this->crawler
            ->filter('table.activityTableStyle tr')
            ->each(function (Crawler $node, $i) use (&$headers) {
                // If it's the first row, extract the headers
                if ($i === 0) {
                    $headers = $node->filter('td')->each(fn (Crawler $node) => $node->text());
                    return null;
                }

                return array_combine(
                    $headers,
                    $node->filter('td')->each(fn (Crawler $node) => str_replace("\u{A0}", '', $node->text()))
                );
            });


        // Remove the null header row
        $activitiesRows = array_filter($activitiesRows);

        $events = [];

        $currentDate = null; // Will be updated after every checkin event

        foreach ($activitiesRows as $activity) {
            // If it has a checkin time, then it's the first activity for the day, so register it as a checkin event
            if ($activity['C/I(Z)'] !== '') {
                // Update the current date, but if it's not set, then the checkin happened that same day
                $currentDate = $activity['Date'] != '' ? $activity['Date'] : $currentDate;

                $events[] = [
                    'type' => EventTypeEnum::CheckIn->value,
                    'from' => $activity['From'],
                    'to' => $activity['To'],
                    'departure' => $this->getCarbonDate($currentDate, $activity['STD(Z)']),
                    'arrival' => $this->getCarbonDate($currentDate, $activity['STA(Z)']),
                    'meta' => [
                        'hotel' => $activity['AC/Hotel']
                    ]
                ];
            }

            $events[] = match (true) {
                // Flights
                // preg_match('/^[A-Za-z]{2}\d+/', $activity['Activity']) => [
                str_starts_with($activity['Activity'], 'DX') => [
                    'type' => EventTypeEnum::Flight->value,
                    'from' => $activity['From'],
                    'to' => $activity['To'],
                    'departure' => $this->getCarbonDate($currentDate, $activity['STD(Z)']),
                    'arrival' => $this->getCarbonDate($currentDate, $activity['STA(Z)']),
                    'meta' => [
                        'flight_number' => $activity['Activity'],
                        'aircraft' => $activity['ACReg'],
                    ],
                ],

                // Off day
                $activity['Activity'] == 'OFF' => [
                    'type' => EventTypeEnum::DayOff->value,
                    'from' => $activity['From'],
                    'to' => $activity['To'],
                    'departure' => $this->getCarbonDate($activity['Date'], substr($activity['STD(Z)'], 0, 4)),
                    'arrival' => $this->getCarbonDate($activity['Date'], substr($activity['STD(Z)'], 0, 4)),
                    'meta' => [],
                ],

                // Standby
                $activity['Activity'] == 'SBY' => [
                    'type' => EventTypeEnum::StandBy->value,
                    'from' => $activity['From'],
                    'to' => $activity['To'],
                    'departure' => $this->getCarbonDate($currentDate, $activity['STD(Z)']),
                    'arrival' => $this->getCarbonDate($currentDate, $activity['STA(Z)']),
                    'meta' => [],
                ],

                    // Others
                default => [
                    'type' => EventTypeEnum::Unknown->value,
                    'from' => $activity['From'],
                    'to' => $activity['To'],
                    'departure' => $this->getCarbonDate($currentDate, $activity['STD(Z)']),
                    'arrival' => $this->getCarbonDate($currentDate, $activity['STA(Z)']),
                    'meta' => [],
                ],
            };

            // If it has a checkout time, then it's the last activity for the day
            if ($activity['C/O(Z)'] !== '') {
                $events[] = [
                    'type' => EventTypeEnum::CheckOut->value,
                    'from' => $activity['From'],
                    'to' => $activity['To'],
                    'departure' => $this->getCarbonDate($currentDate, $activity['STD(Z)']),
                    'arrival' => $this->getCarbonDate($currentDate, $activity['STA(Z)']),
                    'meta' => [
                        'hotel' => $activity['AC/Hotel']
                    ]
                ];
            }
        }

        return $events;
    }


    /**
     * This formulates the date and time based on the entry for the roster
     * @param string $date
     * @param string $time
     * @return string
     */
    public function getCarbonDate(string $date, string $time): string
    {
        $dateString = "$date {$this->startingPeriod->format('M')} {$this->startingPeriod->format('Y')} $time";

        $carbonDate =  new Carbon($dateString, 'UTC');

        return $carbonDate->toDateTimeString();
    }
}
