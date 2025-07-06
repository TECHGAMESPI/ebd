<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LogCookies
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->is('livewire/update')) {
            Log::info('Cookies na requisição para /livewire/update:', $request->cookies->all());
        }
        return $next($request);
    }
}
