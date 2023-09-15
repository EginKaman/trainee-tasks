<?php

declare(strict_types=1);

namespace App\Http\Controllers\Book;

use App\Enum\WorkerStatus;
use App\Http\Controllers\Controller;
use App\Models\{Bot, Category, Site};
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

class BotDataController extends Controller
{
    public function __invoke(Site $site, Category $category, DataTables $dataTables): JsonResponse
    {
        return $dataTables->eloquent($category->bots()->with(['category'])->withCount([
            'workers',
            'workers as sum_tasks' => function ($query): void {
                $query->where('workers.status', WorkerStatus::Finished);
            },
        ]))
            ->editColumn('created_at', fn (Bot $bot): string => $bot->created_at->format('d F Y'))
            ->addColumn('actions', 'books.bots.actions')
            ->rawColumns(['actions'])
            ->toJson();
    }
}
