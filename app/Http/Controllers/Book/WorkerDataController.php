<?php

declare(strict_types=1);

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\{Job, Worker};
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class WorkerDataController extends Controller
{
    public function __invoke(Job $job, DataTables $dataTables): JsonResponse
    {
        return $dataTables->eloquent($job->workers())
            ->editColumn('created_at', fn (Worker $worker): string => $worker->created_at->format('d F Y'))
            ->editColumn('completed_at', fn (Worker $worker): ?string => $worker->completed_at?->format('d F Y'))
            ->editColumn('status', fn (Worker $worker) => str($worker->status->name)->headline()->lower()->ucfirst())
            ->addColumn('actions', 'books.workers.actions')
            ->rawColumns(['actions'])
            ->toJson();
    }
}
