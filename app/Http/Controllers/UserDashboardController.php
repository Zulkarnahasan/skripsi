<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user()->load(['studentProfile', 'documents', 'alternative.sawResult', 'notifications']);

        return view('user.dashboard', compact('user'));
    }
}
