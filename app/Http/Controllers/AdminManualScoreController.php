<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\AlternativeScore;
use App\Models\Criteria;
use Illuminate\Http\Request;

class AdminManualScoreController extends Controller
{
    public function index(Request $request)
    {
        $criteria = Criteria::query()
            ->whereIn('code', ['C7', 'C8'])
            ->orderBy('code')
            ->get();

        $alternatives = Alternative::query()
            ->search($request->query('q'))
            ->where('status', 'verified')
            ->with(['user', 'studentProfile', 'scores.criteria'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.manual-scores.index', compact('alternatives', 'criteria'));
    }

    public function store(Request $request)
    {
        $criteriaIds = Criteria::query()
            ->whereIn('code', ['C7', 'C8'])
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->all();

        $data = $request->validate([
            'scores' => ['required', 'array'],
            'scores.*' => ['array'],
            'scores.*.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        foreach ($data['scores'] as $alternativeId => $items) {
            foreach ($items as $criteriaId => $value) {
                if (! in_array((string) $criteriaId, $criteriaIds, true)) {
                    continue;
                }

                AlternativeScore::query()->updateOrCreate(
                    ['alternative_id' => $alternativeId, 'criteria_id' => $criteriaId],
                    ['raw_value' => $value ?: 0, 'score' => $value ?: 0]
                );
            }
        }

        return back()->with('success', 'Nilai wawancara dan baca Quran tersimpan. Jalankan Proses SAW untuk memperbarui ranking.');
    }
}
