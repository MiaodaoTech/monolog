<?php

namespace MdTech\MdLog\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use MdTech\MdLog\Facades\MdLog;

class MdLogMiddleware
{
    public function handle($request, Closure $next, string $channel)
    {
        $return = $next($request);

        if(!config('md_log.auto_log')){
            return $return;
        }

        $user = Auth::user();
        $url = $request->path();

        $data = [
            'request' => $request->input(),
            'url' => $url,
            'status' => $return->getStatusCode()
        ];

        $message = '[' . $user->nickname . ']请求了' . $url;

        MdLog::create($channel)->info($message, $user->id, 0, $data);

        return $return;
    }
}
