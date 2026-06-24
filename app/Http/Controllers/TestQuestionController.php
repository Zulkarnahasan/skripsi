<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\TestQuestion;
use App\Models\TestSetting;
use Illuminate\Http\Request;

class TestQuestionController extends Controller
{
    public function index()
    {
        $setting = TestSetting::current();
        $criteria = Criteria::query()
            ->with(['testQuestions' => fn ($query) => $query
                ->orderBy('sort_order')
                ->orderBy('id')])
            ->orderBy('code')
            ->get();

        return view('admin.test-questions.index', compact('criteria', 'setting'));
    }

    public function updateSetting(Request $request)
    {
        $data = $request->validate([
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:600'],
            'is_open' => ['nullable', 'boolean'],
            'instruction' => ['nullable', 'string', 'max:1000'],
        ]);

        $data['is_open'] = $request->boolean('is_open');

        TestSetting::current()->update($data);

        return back()->with('success', 'Pengaturan tes diperbarui.');
    }

    public function create()
    {
        $criteria = Criteria::query()->orderBy('code')->get();

        return view('admin.test-questions.create', compact('criteria'));
    }

    public function store(Request $request)
    {
        TestQuestion::query()->create($this->validated($request));

        return redirect()->route('test-questions.index')->with('success', 'Soal tes ditambahkan.');
    }

    public function edit(TestQuestion $testQuestion)
    {
        $criteria = Criteria::query()->orderBy('code')->get();

        return view('admin.test-questions.edit', compact('testQuestion', 'criteria'));
    }

    public function update(Request $request, TestQuestion $testQuestion)
    {
        $testQuestion->update($this->validated($request));

        return redirect()->route('test-questions.index')->with('success', 'Soal tes diperbarui.');
    }

    public function destroy(TestQuestion $testQuestion)
    {
        $testQuestion->delete();

        return back()->with('success', 'Soal tes dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'criteria_id' => ['required', 'exists:criteria,id'],
            'question' => ['required', 'string', 'max:1000'],
            'option_a' => ['required', 'string', 'max:255'],
            'option_b' => ['required', 'string', 'max:255'],
            'option_c' => ['required', 'string', 'max:255'],
            'option_d' => ['required', 'string', 'max:255'],
            'correct_answer' => ['required', 'in:A,B,C,D'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
