<?php

namespace YottaHQ\Bookable\Contracts;

use DateTimeInterface;
use Illuminate\Support\Collection;

interface AvailabilityStrategy
{
    /**
     * Get available slots for a given period and bookable entity.
     *
     * @param DateTimeInterface $periodStart
     * @param DateTimeInterface $periodEnd
     * @param BookableContract $bookable
     *
     * @return Collection of Slot objects
     */
    public function getAvailableSlots(DateTimeInterface $periodStart, DateTimeInterface $periodEnd, BookableContract $bookable): Collection;
}
