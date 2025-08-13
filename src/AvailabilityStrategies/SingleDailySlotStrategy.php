<?php

namespace YottaHQ\Bookable\AvailabilityStrategies;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DateTimeInterface;
use YottaHQ\Bookable\Contracts\AvailabilityStrategy;
use YottaHQ\Bookable\Contracts\BookableContract;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use YottaHQ\Bookable\Entities\Slot;

class SingleDailySlotStrategy implements AvailabilityStrategy
{
    public function getAvailableSlots(DateTimeInterface $periodStart, DateTimeInterface $periodEnd, BookableContract $bookable): Collection
    {
        if ($periodStart > $periodEnd) {
            throw new InvalidArgumentException('Period start must be before or equal to period end.');
        }

        if (Carbon::parse($periodStart)->diffInHours($periodEnd) < 24) {
            throw new InvalidArgumentException('Period must be at least one full day (24 hours) long.');
        }

        $slots = collect();
        $period = CarbonPeriod::create($periodStart, $periodEnd);

        $allBookings = $bookable->bookings()
            ->whereBetween('start_time', [$periodStart, $periodEnd])
            ->get();

        foreach ($period as $date) {
            $dateStr = $date->toDateString();

            $containsDate = $allBookings->contains(function ($booking) use ($dateStr) {
                return $booking->start_time->toDateString() === $dateStr;
            });

            if ($containsDate) {
                continue;
            }

            $slots[] = new Slot(
                $date->copy()->startOfDay(),
                $date->copy()->endOfDay()
            );
        }

        return $slots;
    }
}
