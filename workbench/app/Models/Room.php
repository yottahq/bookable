<?php

namespace Workbench\App\Models;

use YottaHQ\Bookable\Contracts\BookableContract;
use YottaHQ\Bookable\Traits\HasBookings;
use Illuminate\Database\Eloquent\Model;

class Room extends Model implements BookableContract
{
    use HasBookings;

    protected $guarded = [];
    protected $table = 'rooms';

    public function getAvailabilityStrategy(): string|object
    {
        return \YottaHQ\Bookable\AvailabilityStrategies\SingleDailySlotStrategy::class;
    }
}
