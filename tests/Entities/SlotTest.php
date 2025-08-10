<?php

namespace YottaHQ\Bookable\Tests\Entities;

use Carbon\CarbonPeriod;
use DateTime;
use YottaHQ\Bookable\Entities\Slot;
use YottaHQ\Bookable\Tests\TestCase;
use League\Period\Period;

class SlotTest extends TestCase
{
    public function testItCanReturnFormattedStringRepresentation(): void
    {
        $start = new DateTime('2025-08-01 10:00');
        $end = new DateTime('2025-08-01 10:30');

        $slot = new Slot($start, $end);

        $this->assertEquals('2025-08-01 10:00 - 2025-08-01 10:30', $slot->toString());
    }

    public function testItCanReturnFormattedArrayRepresentation(): void
    {
        $start = new DateTime('2025-08-01 10:00');
        $end = new DateTime('2025-08-01 10:30');

        $slot = new Slot($start, $end);

        $expected = [
            'start' => '2025-08-01 10:00:00',
            'end' => '2025-08-01 10:30:00',
        ];

        $this->assertEquals($expected, $slot->toArray(true));
    }

    public function testItCanReturnUnformattedArrayRepresentation(): void
    {
        $start = new DateTime('2025-08-01 10:00');
        $end = new DateTime('2025-08-01 10:30');

        $slot = new Slot($start, $end);

        $expected = [
            'start' => $start,
            'end' => $end,
        ];

        $this->assertEquals($expected, $slot->toArray());
    }

    public function testItCanConvertToCarbonPeriod(): void
    {
        $start = new DateTime('2025-08-01 10:00');
        $end = new DateTime('2025-08-01 10:30');

        $slot = new Slot($start, $end);

        $period = $slot->toCarbonPeriod();

        $this->assertEquals($start, $period->getStartDate());
        $this->assertEquals($end, $period->getEndDate());
        $this->assertEquals($period::class, CarbonPeriod::class);
    }

    public function testItCanConvertToLeaguePeriod(): void
    {
        $start = new DateTime('2025-08-01 10:00');
        $end = new DateTime('2025-08-01 10:30');

        $slot = new Slot($start, $end);

        $period = $slot->toLeaguePeriod();

        $this->assertEquals($start, $period->startDate);
        $this->assertEquals($end, $period->endDate);
        $this->assertEquals($period::class, Period::class);
    }

    public function testItCanCalculateDurationInMinutes(): void
    {
        $start = new DateTime('2025-08-01 10:00');
        $end = new DateTime('2025-08-01 10:30');

        $slot = new Slot($start, $end);

        $this->assertEquals(30, $slot->durationInMinutes());
    }

    public function testItCanCheckOverlappingSlots(): void
    {
        $start1 = new DateTime('2025-08-01 10:00');
        $end1 = new DateTime('2025-08-01 10:30');
        $slot1 = new Slot($start1, $end1);

        $start2 = new DateTime('2025-08-01 10:15');
        $end2 = new DateTime('2025-08-01 10:45');
        $slot2 = new Slot($start2, $end2);

        $this->assertTrue($slot1->overlaps($slot2));

        $start3 = new DateTime('2025-08-01 10:31');
        $end3 = new DateTime('2025-08-01 11:00');
        $slot3 = new Slot($start3, $end3);

        $this->assertFalse($slot1->overlaps($slot3));
    }

    public function testItCanConvertToJson(): void
    {
        $start = new DateTime('2025-08-01 10:00');
        $end = new DateTime('2025-08-01 10:30');

        $slot = new Slot($start, $end);

        $expectedJson = json_encode([
            'start' => '2025-08-01 10:00:00',
            'end' => '2025-08-01 10:30:00',
        ], JSON_THROW_ON_ERROR);

        $this->assertEquals($expectedJson, $slot->toJson());
    }
}
