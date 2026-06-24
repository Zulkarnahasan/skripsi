<?php

namespace App\Http\Controllers;

use App\Models\SawResult;

class ReportController extends Controller
{
    public function index()
    {
        $results = SawResult::query()->with('alternative.user')->orderBy('rank')->paginate(20);

        return view('admin.reports.index', compact('results'));
    }

    public function print()
    {
        $results = SawResult::query()->with('alternative.user')->orderBy('rank')->get();

        return view('admin.reports.print', compact('results'));
    }
}
