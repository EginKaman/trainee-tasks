<?php

declare(strict_types=1);

namespace App\Http\Controllers\Book;

use App\Enum\WorkerStatus;
use App\Http\Controllers\Controller;
use App\Models\{Bot, Job, Worker};
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse, Request};

class WorkerController extends Controller
{
    public function index(Job $job): View
    {
        $config = [
            'order' => [[1, 'desc']],
            'columns' => [
                [
                    'data' => 'name', 'name' => 'name', 'title' => 'Name',
                ],
                [
                    'data' => 'created_at', 'name' => 'created_at', 'title' => 'Created at',
                ],
                [
                    'data' => 'completed_at', 'name' => 'completed_at', 'title' => 'Completed at',
                ],
                [
                    'data' => 'status', 'name' => 'status', 'title' => 'Status',
                ],
                [
                    'data' => 'actions', 'name' => 'actions', 'title' => 'Actions', 'orderable' => false, 'searchable' => false,
                ],
            ],
            'serverSide' => true,
            'ajax' => route('jobs.workers.data', [
                'job' => $job,
            ]),
            'paging' => true,
            'lengthMenu' => [[10, 25, 50], [10, 25, 50]],
        ];

        $heads = ['Name', 'Created at', 'Completed at', 'Status', 'Actions'];

        return view('books.workers.index', compact(['job', 'config', 'heads']));
    }

    public function create(): void
    {
    }

    public function store(Request $request): void
    {
    }

    public function show(Worker $worker): void
    {
    }

    public function edit(Worker $worker): void
    {
    }

    public function update(Request $request, Worker $worker): RedirectResponse
    {
        $worker->status = WorkerStatus::Stopped;
        $worker->save();

        return redirect()->route('jobs.workers.index', [
            'job' => $worker->job,
        ]);
    }

    public function destroy(Worker $worker): void
    {
    }
}
