<?php

declare(strict_types=1);

namespace App\Http\Controllers\Book;

use App\Http\Controllers\Controller;
use App\Models\{Bot, Category, Site};
use Illuminate\Contracts\View\View;
use Illuminate\Http\{RedirectResponse, Request};

class BotController extends Controller
{
    public function index(Site $site, Category $category): View
    {
        $config = [
            'order' => [[1, 'desc']],
            'columns' => [
                [
                    'data' => 'title', 'name' => 'title', 'title' => 'Title',
                ],
                [
                    'data' => 'created_at', 'name' => 'created_at', 'title' => 'Creation date',
                ],
                [
                    'data' => 'workers_count', 'name' => 'workers_count', 'title' => 'Total workers',
                ],
                [
                    'data' => 'sum_tasks', 'name' => 'sum_tasks', 'title' => 'Total completed tasks',
                ],
                [
                    'data' => 'actions', 'name' => 'actions', 'title' => 'Actions', 'orderable' => false,
                    'searchable' => false,
                ],
            ],
            'serverSide' => true,
            'ajax' => route('bots.data', [
                'site' => $site, 'category' => $category,
            ]),
            'paging' => true,
            'lengthMenu' => [[10, 25, 50], [10, 25, 50]],
        ];

        $heads = ['Title', 'Creation date', 'Total workers', 'Total completed tasks', 'Actions'];

        return view('books.bots.index', compact(['site', 'category', 'config', 'heads']));
    }

    public function destroy(Bot $bot): RedirectResponse
    {
        $bot->delete();

        return redirect()->route('sites.categories.bots.index', [
            'site' => $bot->category->site,
            'category' => $bot->category,
        ]);
    }
}
