<?php

namespace YottaHQ\Bookable\Tests\Support;

use YottaHQ\Bookable\AvailabilityStrategies\SingleDailySlotStrategy;
use YottaHQ\Bookable\Support\AvailabilityStrategyResolver;
use YottaHQ\Bookable\Tests\TestCase;
use Workbench\App\Models\Room;

class AvailabilityStrategyResolverTest extends TestCase
{
    public function testItResolvesStrategyUsingDirectClassName(): void
    {
        $resolver = new AvailabilityStrategyResolver();

        $bookable = new Room();

        $strategy = $resolver->resolveFor($bookable);

        $this->assertInstanceOf(SingleDailySlotStrategy::class, $strategy);
    }
}
