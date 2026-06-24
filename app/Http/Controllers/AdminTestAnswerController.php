<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use Illuminate\Http\Request;

class AdminTestAnswerController extends Controller
{
    public function index(Request $request)
    {
        $alternatives = Alternative::query()
            ->search($request->query('q'))
            ->whereHas('testAnswers')
            ->with([
                'user',
                'studentProfile',
                'scores.criteria',
                'testAnswers.question.criteria',
            ])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.test-answers.index', compact('alternatives'));
    }
}
