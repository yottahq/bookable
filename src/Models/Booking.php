<?php

namespace YottaHQ\Bookable\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'bookable_id',
        'bookable_type',
        'start_time',
        'end_time',
        'canceled_at',
        'cancellation_reason',
        'timezone',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    public function bookable(): \Illuminate\Database\Eloquent\Relations\MorphTo
    {
        return $this->morphTo();
    }

    public function scopeActive($query): \LaravelIdea\Helper\YottaHQ\Bookable\Models\_IH_Booking_QB
    {
        return $query->whereNull('canceled_at');
    }
}
