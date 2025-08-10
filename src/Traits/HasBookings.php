<?php

namespace YottaHQ\Bookable\Traits;

use YottaHQ\Bookable\Contracts\BookableContract;
use YottaHQ\Bookable\Models\Booking;

/**
 * @mixin BookableContract
 */
trait HasBookings
{
    public function bookings(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }
}
