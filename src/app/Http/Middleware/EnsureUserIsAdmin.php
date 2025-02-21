<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if (!Auth::check() || !$user || !$user->hasRole('admin')) {
            if ($request->expectsJson()) {
                return response()->json(['error' => '権限がありません'], 403);
            }
            return redirect()->route('home')->with('error', '管理者権限が必要です');
        }

        return $next($request);
    }
}
