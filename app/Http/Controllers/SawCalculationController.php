<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\AlternativeScore;
use App\Models\Criteria;
use App\Models\TestSetting;
use App\Services\SawCalculationService;
use Illuminate\Http\Request;

class SawCalculationController extends Controller
{
    public function processForm()
    {
        $criteria = Criteria::query()->orderBy('code')->get();
        $alternatives = Alternative::query()->where('status', 'verified')->with(['user', 'scores'])->get();

        return view('admin.saw.process', compact('criteria', 'alternatives'));
    }

    public function storeScores(Request $request)
    {
        $data = $request->validate([
            'scores' => ['required', 'array'],
            'scores.*.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        foreach ($data['scores'] as $alternativeId => $items) {
            foreach ($items as $criteriaId => $value) {
                AlternativeScore::query()->updateOrCreate(
                    ['alternative_id' => $alternativeId, 'criteria_id' => $criteriaId],
                    ['raw_value' => $value ?: 0, 'score' => $value ?: 0]
                );
            }
        }

        return back()->with('success', 'Nilai alternatif tersimpan.');
    }

    public function process(Request $request, SawCalculationService $service)
    {
        $quota = (int) $request->input('quota', TestSetting::current()->selection_quota);
        $result = $service->process($quota);

        return $request->expectsJson()
            ? response()->json($result)
            : redirect()->route('saw.results')->with('success', 'Perhitungan SAW selesai.');
    }
}
