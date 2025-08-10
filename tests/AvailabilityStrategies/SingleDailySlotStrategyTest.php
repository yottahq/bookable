<?php

namespace YottaHQ\Bookable\Tests\AvailabilityStrategies;


use Carbon\Carbon;
use YottaHQ\Bookable\AvailabilityStrategies\SingleDailySlotStrategy;
use YottaHQ\Bookable\Contracts\BookableContract;
use YottaHQ\Bookable\Entities\Slot;
use YottaHQ\Bookable\Tests\TestCase;
use Illuminate\Support\Collection;
use Mockery;

class SingleDailySlotStrategyTest extends TestCase
{
    public function testThrowsExceptionIfEndDateIsLessThanStartDate(): void
    {
        $strategy = new SingleDailySlotStrategy();
        $room = Mockery::mock(BookableContract::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Period start must be before or equal to period end.');
        $strategy->getAvailableSlots(Carbon::parse('2025-08-03'), Carbon::parse('2025-08-01'), $room);
    }

    public function testThrowsExceptionIfPeriodDiffIsLessThanOneDay(): void
    {
        $strategy = new SingleDailySlotStrategy();
        $room = Mockery::mock(BookableContract::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Period must be at least one full day (24 hours) long.');
        $strategy->getAvailableSlots(Carbon::parse('2025-08-01'), Carbon::parse('2025-08-01')->addHours(2), $room);
    }

    public function testItReturnsAllAvailableDaysWithinThePeriodWithNoExistingBookingsOrViolations(): void
    {
        $startDate = Carbon::parse('2025-08-01');
        $endDate = Carbon::parse('2025-08-03')->endOfDay();

        $expectedCollection = collect();
        $expectedCollection->add(new Slot($startDate->copy()->startOfDay(), $startDate->copy()->endOfDay()));
        $expectedCollection->add(new Slot($startDate->copy()->addDay()->startOfDay(), $startDate->copy()->addDay()->endOfDay()));
        $expectedCollection->add(new Slot($startDate->copy()->addDays(2)->startOfDay(), $startDate->copy()->addDays(2)->endOfDay()));

        $bookable = Mockery::mock(BookableContract::class);

        $bookable->shouldReceive('bookings->whereBetween->get->groupBy')
            ->andReturn(collect());

        $strategy = new SingleDailySlotStrategy();

        $available = $strategy->getAvailableSlots($startDate->startOfDay(), $endDate, $bookable);

        $this->assertEquals($expectedCollection->all(), $available->all());
    }

    public function testReturnsEmptyCollectionIfNoDaysAvailable(): void
    {
        $mockedCollection = collect([
            '2025-08-03',
            '2025-08-04',
            '2025-08-05',
        ]);

        $bookable = Mockery::mock(BookableContract::class);
        $bookable->shouldReceive('bookings->whereBetween->get->groupBy')
            ->andReturn($mockedCollection);

        $strategy = new SingleDailySlotStrategy();

        $available = $strategy->getAvailableSlots(Carbon::parse('2025-08-03')->startOfDay(), Carbon::parse('2025-08-05')->endOfDay(), $bookable);

        $this->assertEmpty($available);
    }
}
