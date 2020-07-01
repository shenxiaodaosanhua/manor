<?php

namespace App\Http\Middleware;

use App\Jobs\ActionPodcast;
use Closure;

class ActionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $path = $request->path();
        ActionPodcast::dispatch($request->user, $path);

        return $next($request);
    }
}
