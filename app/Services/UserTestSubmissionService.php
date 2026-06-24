<?php

namespace App\Services;

use App\Models\AlternativeScore;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserTestSubmissionService
{
    public function submit(User $user, array $answers = [], bool $preserveExistingWhenMissing = false): void
    {
        $alternative = $user->alternative;

        if (! $alternative) {
            return;
        }

        $questions = TestQuestion::query()->where('is_active', true)->get();
        $questionIds = $questions->pluck('id')->all();

        DB::transaction(function () use ($alternative, $answers, $questions, $questionIds, $preserveExistingWhenMissing) {
            foreach ($questions as $question) {
                $questionId = $question->id;
                $hasAnswer = array_key_exists($questionId, $answers) || array_key_exists((string) $questionId, $answers);
                $selectedAnswer = $answers[$questionId] ?? $answers[(string) $questionId] ?? null;

                if (! $hasAnswer && $preserveExistingWhenMissing) {
                    TestAnswer::query()->firstOrCreate(
                        ['alternative_id' => $alternative->id, 'test_question_id' => $questionId],
                        ['selected_answer' => null, 'score' => 0]
                    );

                    continue;
                }

                $score = $selectedAnswer && $selectedAnswer === $question->correct_answer ? 100 : 0;

                TestAnswer::query()->updateOrCreate(
                    ['alternative_id' => $alternative->id, 'test_question_id' => $questionId],
                    ['selected_answer' => $selectedAnswer, 'score' => $score]
                );
            }

            $averages = TestQuestion::query()
                ->whereIn('id', $questionIds)
                ->with(['answers' => fn ($query) => $query->where('alternative_id', $alternative->id)])
                ->get()
                ->groupBy('criteria_id')
                ->map(fn ($questions) => round($questions
                    ->map(fn ($question) => (float) ($question->answers->first()?->score ?? 0))
                    ->avg(), 4));

            foreach ($averages as $criteriaId => $score) {
                AlternativeScore::query()->updateOrCreate(
                    ['alternative_id' => $alternative->id, 'criteria_id' => $criteriaId],
                    ['raw_value' => $score, 'score' => $score]
                );
            }
        });
    }
}
