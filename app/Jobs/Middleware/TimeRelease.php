<?php

declare(strict_types=1);

namespace App\Jobs\Middleware;

use DateTimeInterface;

class TimeRelease
{
    public function __construct(
        protected DateTimeInterface $from,
        protected DateTimeInterface $to
    )
    {
    }

    public function handle(mixed $job, mixed $next): void
    {
        if (now()->isBetween($this->from, $this->to)) {
            return;
        }

        $next($job);
    }
}
