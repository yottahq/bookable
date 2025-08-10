<?php

namespace YottaHQ\Bookable\Entities;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTimeInterface;
use Exception;
use JsonException;
use League\Period\Period;

class Slot
{
    public function __construct(
        public DateTimeInterface $start,
        public DateTimeInterface $end,
    )
    {
    }

    public function toArray(bool $formatted = false): array
    {
        if ($formatted) {
            return [
                'start' => Carbon::instance($this->start)->format('Y-m-d H:i:s'),
                'end' => Carbon::instance($this->end)->format('Y-m-d H:i:s'),
            ];
        }

        return [
            'start' => $this->start,
            'end' => $this->end,
        ];
    }

    /**
     * @throws JsonException
     */
    public function toJson(int $options = 0): string
    {
        return json_encode([
            'start' => Carbon::instance($this->start)->format('Y-m-d H:i:s'),
            'end' => Carbon::instance($this->end)->format('Y-m-d H:i:s'),
        ], JSON_THROW_ON_ERROR | $options);
    }

    public function toCarbonPeriod(): CarbonPeriod
    {
        return new CarbonPeriod($this->start, $this->end);
    }

    /**
     * @throws Exception
     */
    public function toLeaguePeriod(): Period
    {
        return Period::fromDate($this->start, $this->end);
    }

    public function durationInMinutes(): int
    {
        return Carbon::instance($this->start)->diffInMinutes(Carbon::instance($this->end));
    }

    /**
     * @throws Exception
     */
    public function overlaps(Slot $other): bool
    {
        return $this->toLeaguePeriod()->overlaps($other->toLeaguePeriod());
    }

    public function toString(): string
    {
        return sprintf('%s - %s', $this->start->format('Y-m-d H:i'), $this->end->format('Y-m-d H:i'));
    }
}
