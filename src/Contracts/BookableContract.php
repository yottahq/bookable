<?php

namespace YottaHQ\Bookable\Contracts;

interface BookableContract
{
    public function bookings();

    public function getAvailabilityStrategy(): string|object;
}
