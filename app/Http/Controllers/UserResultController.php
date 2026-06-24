<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserResultController extends Controller
{
    public function status(Request $request)
    {
        $user = $request->user()->load(['studentProfile', 'documents', 'alternative']);

        return view('user.status', compact('user'));
    }

    public function result(Request $request)
    {
        $alternative = $request->user()->alternative?->load('sawResult');

        return view('user.result', compact('alternative'));
    }
}
