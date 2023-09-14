<?php

declare(strict_types=1);

namespace App\DataTransferObjects\Book;

use App\Enum\{JobStatus, JobType};
use Spatie\LaravelData\Data;

class JobData extends Data
{
    public function __construct(
        public ?JobType $type,
        public ?string $name,
        public int $workers_count,
        public ?string $cron,
        public ?int $pause,
        public ?int $repetitions,
        public JobStatus $status = JobStatus::Created,
        public bool $is_loop = false,
    ) {
    }
}
