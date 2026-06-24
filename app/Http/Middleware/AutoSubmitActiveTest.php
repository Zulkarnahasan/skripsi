<?php

namespace App\Http\Middleware;

use App\Services\UserTestSubmissionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AutoSubmitActiveTest
{
    public function handle(Request $request, Closure $next): Response
    {
        $isActiveTest = (bool) $request->session()->get('user_test_started', false);
        $isTestRequest = $request->is('user/test') || $request->is('user/test/*');

        if ($isActiveTest && ! $isTestRequest && $request->user()) {
            app(UserTestSubmissionService::class)->submit($request->user(), [], true);
            $request->session()->forget(['user_test_started', 'user_test_started_at']);
            $request->session()->flash('success', 'Tes otomatis tersubmit karena Anda meninggalkan halaman tes.');
        }

        return $next($request);
    }
}
