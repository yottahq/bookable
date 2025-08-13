<?php

namespace YottaHQ\Bookable\Tests\AvailabilityStrategies;


use Carbon\Carbon;
use Workbench\App\Models\Room;
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
        $endDate = Carbon::parse('2025-08-10')->endOfDay();

        $bookable = Room::factory()->create();
        $bookable->bookings()->createMany([
            ['start_time' => $startDate->copy()->startOfDay(), 'end_time' => $startDate->copy()->endOfDay()],
            ['start_time' => $startDate->copy()->addDay()->startOfDay(), 'end_time' => $startDate->copy()->addDay()->endOfDay()],
            ['start_time' => $startDate->copy()->addDays(2)->startOfDay(), 'end_time' => $startDate->copy()->addDays(2)->endOfDay()],
        ]);

        $strategy = new SingleDailySlotStrategy();

        $available = $strategy->getAvailableSlots($startDate->startOfDay(), $endDate, $bookable);

        $this->assertCount(7, $available);
    }

//    public function testReturnsEmptyCollectionIfNoDaysAvailable(): void
//    {
//        $mockedCollection = collect([
//            '2025-08-03',
//            '2025-08-04',
//            '2025-08-05',
//        ]);
//
//        $bookable = Mockery::mock(BookableContract::class);
//        $bookable->shouldReceive('bookings->whereBetween->get->groupBy')
//            ->andReturn($mockedCollection);
//
//        $strategy = new SingleDailySlotStrategy();
//
//        $available = $strategy->getAvailableSlots(Carbon::parse('2025-08-03')->startOfDay(), Carbon::parse('2025-08-05')->endOfDay(), $bookable);
//
//        $this->assertEmpty($available);
//    }
}
