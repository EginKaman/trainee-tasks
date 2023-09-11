<?php

declare(strict_types=1);

namespace App\Http\Controllers\Book;

use App\Enum\WorkerStatus;
use App\Http\Controllers\Controller;
use App\Models\Bot;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class JobDataController extends Controller
{
    public function __invoke(Bot $bot, DataTables $dataTables): JsonResponse
    {
        return $dataTables->eloquent($bot->jobs()->withCount([
            'workers', 'workers as sum_workers' => function ($query): void {
                        $query->where('workers.status', WorkerStatus::Finished);
                    },
        ]))
            ->addColumn('actions', 'books.jobs.actions')
            ->rawColumns(['actions'])
            ->toJson();
    }
}
