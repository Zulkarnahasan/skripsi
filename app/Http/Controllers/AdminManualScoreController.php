<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\AlternativeScore;
use App\Models\Criteria;
use App\Models\ManualAssessmentScore;
use Illuminate\Http\Request;

class AdminManualScoreController extends Controller
{
    private const COMPONENTS = [
        'C7' => [
            'communication' => 'Komunikasi',
            'motivation' => 'Motivasi Kuliah',
            'commitment' => 'Komitmen Menyelesaikan Studi',
            'kip_understanding' => 'Pemahaman KIP-K',
        ],
        'C8' => [
            'fluency' => 'Kelancaran Bacaan',
            'tajwid' => 'Tajwid',
            'makhraj' => 'Makharijul Huruf',
            'adab' => 'Adab Membaca',
        ],
    ];

    public function interview(Request $request)
    {
        return $this->criterionScoreForm($request, 'C7', 'Nilai Wawancara', 'Input nilai wawancara calon penerima KIP-K.');
    }

    public function quran(Request $request)
    {
        return $this->criterionScoreForm($request, 'C8', 'Nilai Baca Quran', 'Input nilai kemampuan membaca Al-Quran.');
    }

    public function index(Request $request)
    {
        $criteria = Criteria::query()
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

    public function storeInterview(Request $request)
    {
        return $this->storeCriterionScores($request, 'C7', 'Nilai wawancara tersimpan. Jalankan Proses SAW untuk memperbarui ranking.');
    }

    public function storeQuran(Request $request)
    {
        return $this->storeCriterionScores($request, 'C8', 'Nilai baca Quran tersimpan. Jalankan Proses SAW untuk memperbarui ranking.');
    }

    public function store(Request $request)
    {
        $criteriaIds = Criteria::query()->pluck('id')->map(fn ($id) => (string) $id)->all();
        $alternativeIds = Alternative::query()->where('status', 'verified')->pluck('id')->map(fn ($id) => (string) $id)->all();

        $data = $request->validate([
            'scores' => ['required', 'array'],
            'scores.*' => ['array'],
            'scores.*.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        foreach ($data['scores'] as $alternativeId => $items) {
            if (! in_array((string) $alternativeId, $alternativeIds, true)) {
                continue;
            }

            foreach ($items as $criteriaId => $grade) {
                if (! in_array((string) $criteriaId, $criteriaIds, true)) {
                    continue;
                }

                $value = $grade === null || $grade === '' ? 0 : (float) $grade;

                AlternativeScore::query()->updateOrCreate(
                    ['alternative_id' => $alternativeId, 'criteria_id' => $criteriaId],
                    ['raw_value' => $value, 'score' => $value]
                );
            }
        }

        return back()->with('success', 'Nilai manual admin tersimpan. Jalankan Proses SAW untuk memperbarui ranking.');
    }

    private function criterionScoreForm(Request $request, string $code, string $title, string $description)
    {
        $criterion = Criteria::query()->where('code', $code)->first();

        $alternatives = Alternative::query()
            ->search($request->query('q'))
            ->where('status', 'verified')
            ->with([
                'user',
                'studentProfile',
                'scores' => fn ($query) => $criterion ? $query->where('criteria_id', $criterion->id) : $query->whereRaw('1 = 0'),
                'manualAssessmentScores' => fn ($query) => $criterion ? $query->where('criteria_id', $criterion->id) : $query->whereRaw('1 = 0'),
            ])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $storeRoute = $code === 'C7' ? 'interview-scores.store' : 'quran-scores.store';
        $components = self::COMPONENTS[$code] ?? [];

        return view('admin.manual-scores.criterion', compact('alternatives', 'criterion', 'title', 'description', 'storeRoute', 'components'));
    }

    private function storeCriterionScores(Request $request, string $code, string $message)
    {
        $criterion = Criteria::query()->where('code', $code)->firstOrFail();
        $alternativeIds = Alternative::query()->where('status', 'verified')->pluck('id')->map(fn ($id) => (string) $id)->all();

        $data = $request->validate([
            'scores' => ['required', 'array'],
            'scores.*' => ['array'],
            'scores.*.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $components = self::COMPONENTS[$code] ?? [];

        foreach ($data['scores'] as $alternativeId => $componentScores) {
            if (! in_array((string) $alternativeId, $alternativeIds, true)) {
                continue;
            }

            $values = [];

            foreach ($components as $componentKey => $componentName) {
                $score = $componentScores[$componentKey] ?? 0;
                $value = $score === null || $score === '' ? 0 : (float) $score;
                $values[] = $value;

                ManualAssessmentScore::query()->updateOrCreate(
                    [
                        'alternative_id' => $alternativeId,
                        'criteria_id' => $criterion->id,
                        'component_key' => $componentKey,
                    ],
                    [
                        'component_name' => $componentName,
                        'score' => $value,
                    ]
                );
            }

            $value = count($values) > 0 ? round(array_sum($values) / count($values), 4) : 0;

            AlternativeScore::query()->updateOrCreate(
                ['alternative_id' => $alternativeId, 'criteria_id' => $criterion->id],
                ['raw_value' => $value, 'score' => $value]
            );
        }

        return back()->with('success', $message);
    }
}
