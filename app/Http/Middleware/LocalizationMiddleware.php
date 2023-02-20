<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocalizationMiddleware
{
    /**
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('Accept-Language')
            && in_array($request->header('Accept-Language'), config('localization.supported_locales'), true)) {
            \App::setLocale($request->header('Accept-Language'));
        }

        return $next($request);
    }
}
