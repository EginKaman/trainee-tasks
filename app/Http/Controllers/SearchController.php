<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\SearchRequest;
use App\Models\{Movie, Person, Serial};
use App\Services\ElasticsearchService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Matchish\ScoutElasticSearch\MixedSearch;

class SearchController extends Controller
{
    public function elastic(SearchRequest $request, ElasticsearchService $client): JsonResponse
    {
        return response()->json($client->search(implode(',', [
            (new Movie())->searchableAs(),
            (new Serial())->searchableAs(),
            (new Person())->searchableAs(),
        ]), [
            'size' => 200,
            'sort' => [
                'popularity' => 'desc',
            ],
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'multi_match' => [
                                'query' => $request->validated('query'),
                                'fields' => ['original_name', 'original_title', 'name', 'also_known_as'],
                            ],
                        ],
                        [
                            'range' => [
                                'release_date' => [
                                    'gte' => (new Carbon())->setYear($request->validated('decade'))->startOfDecade(),
                                    'lte' => (new Carbon())->setYear($request->validated('decade'))->endOfDecade(),
                                ],
                            ],
                        ],
                        [
                            'range' => [
                                'first_air_date' => [
                                    'gte' => (new Carbon())->setYear($request->validated('decade'))->startOfDecade(),
                                    'lte' => (new Carbon())->setYear($request->validated('decade'))->endOfDecade(),
                                ],
                            ],
                        ],
                        [
                            'range' => [
                                'birthday' => [
                                    'gte' => (new Carbon())->setYear($request->validated('decade'))->startOfDecade(),
                                    'lte' => (new Carbon())->setYear($request->validated('decade'))->endOfDecade(),
                                ],
                            ],
                        ],
                    ],
                    'filter' => [
                        [
                            'terms' => [
                                'original_language' => $request->validated('languages'),
                            ],
                        ],
                        //                        [
                        //                            'terms' => [
                        //                                'production_countries.iso_3166_1' => $request->validated('countries'),
                        //                            ],
                        //                        ],
                        //                        [
                        //                            'terms' => [
                        //                                'production_countries.name' => $request->validated('countries'),
                        //                            ],
                        //                        ],
                        //                        [
                        //                            'terms' => [
                        //                                'place_of_birth' => $request->validated('place_of_birth'),
                        //                            ],
                        //                        ],
                        //                        [
                        //                            'terms' => [
                        //                                'genres.name' => $request->validated('genres'),
                        //                            ],
                        //                        ],
                    ],
                ],
            ],
        ]));
    }

    public function search(SearchRequest $request): JsonResponse
    {
        return response()->json(MixedSearch::search($request->validated('query'))->within(implode(',', [
            (new Movie())->searchableAs(),
            (new Serial())->searchableAs(),
            (new Person())->searchableAs(),
        ]))->get()->toArray());

//        return response()->json(Movie::search($request->validated('query'))->get()->toArray());
    }
}
