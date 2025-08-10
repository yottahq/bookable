<?php

namespace YottaHQ\Bookable\Tests\Traits;

use YottaHQ\Bookable\Models\Booking;
use YottaHQ\Bookable\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\Room;

class HasBookingsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->setUpSchema();
    }

    protected function setUpSchema(): void
    {
        // Create a minimal table for test model
        Schema::create('rooms', static function ($table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function testItCanAddBooking(): void
    {
        $room = Room::create();

        $start = Carbon::parse('2025-08-01 10:00:00');
        $end = Carbon::parse('2025-08-01 12:00:00');

        $booking = $room->bookings()->create([
            'start_time' => $start,
            'end_time' => $end,
        ]);

        $this->assertDatabaseHas('bookings', [
            'bookable_id' => $room->id,
            'bookable_type' => Room::class,
            'start_time' => $start,
            'end_time' => $end,
        ]);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertEquals(1, $room->bookings()->count());
    }
}
