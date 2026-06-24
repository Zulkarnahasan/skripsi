<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\SawResult;
use Illuminate\Support\Facades\DB;

class SawCalculationService
{
    public function process(int $quota = 10): array
    {
        return DB::transaction(function () use ($quota) {
            $criteria = Criteria::query()->get();
            $alternatives = Alternative::query()
                ->where('status', 'verified')
                ->with(['scores.criteria', 'user', 'studentProfile'])
                ->get();

            foreach ($criteria as $criterion) {
                $values = $alternatives->flatMap->scores
                    ->where('criteria_id', $criterion->id)
                    ->pluck('score')
                    ->map(fn ($value) => (float) $value)
                    ->filter(fn ($value) => $value > 0);

                $max = $values->max() ?: 1;
                $min = $values->min() ?: 1;

                foreach ($alternatives as $alternative) {
                    $score = $alternative->scores->firstWhere('criteria_id', $criterion->id);
                    if (! $score) {
                        continue;
                    }

                    $value = (float) $score->score;
                    $normalized = $criterion->type === 'benefit'
                        ? $value / $max
                        : ($value > 0 ? $min / $value : 0);

                    $score->update([
                        'normalized_value' => round($normalized, 6),
                        'weighted_value' => round($normalized * (float) $criterion->weight, 6),
                    ]);
                }
            }

            $ranked = Alternative::query()
                ->where('status', 'verified')
                ->withSum('scores as total_score', 'weighted_value')
                ->orderByDesc('total_score')
                ->get();

            foreach ($ranked as $index => $alternative) {
                SawResult::query()->updateOrCreate(
                    ['alternative_id' => $alternative->id],
                    [
                        'final_score' => round((float) $alternative->total_score, 6),
                        'rank' => $index + 1,
                        'status' => $index < $quota ? 'accepted' : 'rejected',
                    ]
                );
            }

            return ['processed' => $ranked->count(), 'quota' => $quota];
        });
    }
}
