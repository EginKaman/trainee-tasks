<?php

declare(strict_types=1);

namespace App\Http\Controllers\Book;

use App\Enum\{JobType, WorkerStatus};
use App\Http\Controllers\Controller;
use App\Models\{Bot, Job};
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class JobDataController extends Controller
{
    public function jobs(Bot $bot, DataTables $dataTables): JsonResponse
    {
        return $dataTables->eloquent($bot->jobs()->withCount([
            'workers', 'workers as sum_workers' => function ($query): void {
                $query->where('workers.status', WorkerStatus::Finished);
            },
        ])->where('type', JobType::Single))
            ->editColumn('is_loop', fn (Job $job): string => $job->is_loop ? 'Yes' : 'No')
            ->editColumn('created_at', fn (Job $job): string => $job->created_at->format('d F Y, H:i:s'))
            ->addColumn('actions', 'books.jobs.actions')
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function cron(Bot $bot, DataTables $dataTables): JsonResponse
    {
        return $dataTables->eloquent($bot->jobs()->withCount([
            'workers', 'workers as sum_workers' => function ($query): void {
                $query->where('workers.status', WorkerStatus::Finished);
            },
        ])->where('type', JobType::Cron))
            ->addColumn('actions', 'books.jobs.actions')
            ->editColumn('created_at', fn (Job $job): string => $job->created_at->format('d F Y, H:i:s'))
            ->addColumn('last_schedule', fn (Job $job): ?string => $job->last_schedule)
            ->rawColumns(['actions'])
            ->toJson();
    }
}
