<?php

declare(strict_types=1);

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\{Bot, Job};
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class WorkerDataController extends Controller
{
    public function __invoke(Job $job, DataTables $dataTables): JsonResponse
    {
        return $dataTables->eloquent($job->workers())
            ->addColumn('actions', 'books.workers.actions')
            ->rawColumns(['actions'])
            ->toJson();
    }
}
