<?php

namespace App\Http\Controllers;

use App\Models\TestAnswer;
use App\Models\TestQuestion;
use App\Models\TestSetting;
use App\Services\UserTestSubmissionService;
use Illuminate\Http\Request;

class UserTestController extends Controller
{
    public function index(Request $request)
    {
        $alternative = $request->user()->alternative;
        $questions = TestQuestion::query()
            ->where('is_active', true)
            ->with('criteria')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        $setting = TestSetting::current();
        $testStarted = (bool) $request->session()->get('user_test_started', false);
        $durationMinutes = $setting->duration_minutes;
        $startedAt = (int) $request->session()->get('user_test_started_at', 0);
        $remainingSeconds = $testStarted
            ? max(0, ($startedAt + ($durationMinutes * 60)) - now()->timestamp)
            : $durationMinutes * 60;

        $answers = $alternative
            ? TestAnswer::query()
                ->where('alternative_id', $alternative->id)
                ->pluck('selected_answer', 'test_question_id')
            : collect();
        $completed = $alternative && $questions->isNotEmpty()
            ? TestAnswer::query()
                ->where('alternative_id', $alternative->id)
                ->whereIn('test_question_id', $questions->pluck('id'))
                ->count() >= $questions->count()
            : false;

        return view('user.test', compact('questions', 'alternative', 'answers', 'testStarted', 'durationMinutes', 'remainingSeconds', 'completed', 'setting'));
    }

    public function start(Request $request)
    {
        if (! $request->user()->alternative) {
            return redirect()->route('user.profile')->with('error', 'Lengkapi profil terlebih dahulu sebelum memulai tes.');
        }

        if (! TestSetting::current()->is_open) {
            return redirect()->route('user.test')->with('error', 'Tes belum dibuka oleh admin.');
        }

        $request->session()->put('user_test_started', true);
        $request->session()->put('user_test_started_at', now()->timestamp);

        return redirect()->route('user.test');
    }

    public function store(Request $request, UserTestSubmissionService $testSubmission)
    {
        $alternative = $request->user()->alternative;

        if (! $alternative) {
            return redirect()->route('user.profile')->with('error', 'Lengkapi profil terlebih dahulu sebelum mengisi tes.');
        }

        if (! TestSetting::current()->is_open) {
            return redirect()->route('user.test')->with('error', 'Tes belum dibuka oleh admin.');
        }

        $request->session()->put('user_test_started', true);

        $questions = TestQuestion::query()->where('is_active', true)->get();
        $questionIds = $questions->pluck('id')->all();
        $timeExpired = $request->boolean('time_expired') || $request->boolean('auto_submit');

        $rules = [
            'answers' => ['nullable', 'array'],
            'time_expired' => ['nullable', 'boolean'],
            'auto_submit' => ['nullable', 'boolean'],
        ];

        foreach ($questionIds as $questionId) {
            $rules["answers.{$questionId}"] = [$timeExpired ? 'nullable' : 'required', 'in:A,B,C,D'];
        }

        $data = $request->validate($rules);
        $data['answers'] = $data['answers'] ?? [];

        $testSubmission->submit($request->user(), $data['answers']);

        $request->session()->forget(['user_test_started', 'user_test_started_at']);

        return redirect()->route('user.test')->with('success', 'Jawaban tes tersimpan dan nilai SAW diperbarui.');
    }

    public function autoSubmit(Request $request, UserTestSubmissionService $testSubmission)
    {
        if (! $request->session()->get('user_test_started', false)) {
            return response()->json(['ok' => true, 'submitted' => false]);
        }

        $data = $request->validate([
            'answers' => ['nullable', 'array'],
            'answers.*' => ['nullable', 'in:A,B,C,D'],
        ]);

        $testSubmission->submit($request->user(), $data['answers'] ?? []);
        $request->session()->forget(['user_test_started', 'user_test_started_at']);

        return response()->json(['ok' => true, 'submitted' => true]);
    }
}
