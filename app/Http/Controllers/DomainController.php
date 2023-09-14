<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DomainListRequest;
use App\Models\{Country, Domain};
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class DomainController extends Controller
{
    public function index(DomainListRequest $request): Response
    {
        $domains = Cache::remember("domains.{$request->code}.{$request->type}", 3600, function () use ($request) {
            return Domain::query()
                ->where('country_id', Country::where('iso_2_code', $request->code)->first()->id)
                ->where('type', $request->type)
                ->where('is_available', true)
                ->get()->implode('domain', ',');
        });

        return response($domains)->header('Content-Type', 'text/plain');
    }
}
