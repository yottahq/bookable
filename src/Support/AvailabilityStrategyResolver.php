<?php

namespace YottaHQ\Bookable\Support;

use YottaHQ\Bookable\Contracts\AvailabilityStrategy;
use YottaHQ\Bookable\Contracts\BookableContract;

class AvailabilityStrategyResolver
{
    public function __construct(protected array $strategyMap = [])
    {
    }

    public function resolveFor(BookableContract $bookable): AvailabilityStrategy
    {
        $strategy = $bookable->getAvailabilityStrategy();

        $class = $this->resolveStrategyClass($strategy);

        return app($class);
    }

    public function resolveStrategyClass(string $keyOrClass): string
    {
        return $this->strategyMap[$keyOrClass] ?? $keyOrClass;
    }
}
