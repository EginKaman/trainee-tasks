<?php

declare(strict_types=1);

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Http\Requests\Book\Job\{StoreRequest, UpdateRequest};
use App\Models\{Bot, Job, Worker};
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse};

class JobController extends Controller
{
    public function index(Bot $bot): View
    {
        $config = [
            'order' => [[1, 'desc']],
            'columns' => [
                [
                    'data' => 'name', 'name' => 'name', 'title' => 'Title',
                ],
                [
                    'data' => 'created_at', 'name' => 'created_at', 'title' => 'Creation date and time',
                ],
                [
                    'data' => 'workers_count', 'name' => 'workers_count', 'title' => 'Total workers',
                ],
                [
                    'data' => 'sum_workers', 'name' => 'sum_workers', 'title' => 'Total failed workers',
                ],
                [
                    'data' => 'is_loop', 'name' => 'is_loop', 'title' => 'Loop',
                ],
                [
                    'data' => 'actions', 'name' => 'actions', 'title' => 'Actions', 'orderable' => false,
                    'searchable' => false,
                ],
            ],
            'serverSide' => true,
            'ajax' => route('bots.jobs.data', [
                'bot' => $bot,
            ]),
            'paging' => true,
            'lengthMenu' => [[10, 25, 50], [10, 25, 50]],
        ];

        $heads = ['Title', 'Creation date and time', 'Total workers', 'Total failed workers', 'Loop', 'Actions'];

        return view('books.jobs.index', compact(['bot', 'config', 'heads']));
    }

    public function create(Bot $bot): View
    {
        $bot->loadMissing(['category', 'category.site']);

        return view('books.jobs.create', compact(['bot']));
    }

    public function store(StoreRequest $request, Bot $bot): RedirectResponse
    {
        $job = $bot->jobs()->create($request->getValidatedData()->toArray());

        $job->workers()->createMany(Worker::factory()->count($job->count_workers)->make()->toArray());

        return redirect()->route('bots.jobs.index', [
            'bot' => $bot,
        ]);
    }

    public function edit(Job $job): View
    {
        $job->loadMissing(['bot.category.site']);

        return view('books.jobs.edit', compact(['job']));
    }

    public function update(UpdateRequest $request, Job $job): RedirectResponse
    {
        $job->fill($request->getValidatedData()->toArray())->save();

        $job->workers()->delete();

        $job->workers()->createMany(Worker::factory()->count($job->count_workers)->make()->toArray());

        return redirect()->route('bots.jobs.index', [
            'bot' => $job->bot_id,
        ]);
    }

    public function destroy(Job $job): RedirectResponse
    {
        $job->delete();

        return redirect()->route('bots.jobs.index', [
            'bot' => $job->bot_id,
        ]);
    }
}
