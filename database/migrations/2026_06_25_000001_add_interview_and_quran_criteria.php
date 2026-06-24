<?php

use App\Models\Criteria;
use App\Models\SubCriteria;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $criteria = [
            'C1' => ['name' => 'Bahasa Indonesia', 'description' => 'Nilai Bahasa Indonesia.'],
            'C2' => ['name' => 'IPA', 'description' => 'Nilai Ilmu Pengetahuan Alam.'],
            'C3' => ['name' => 'IPS', 'description' => 'Nilai Ilmu Pengetahuan Sosial.'],
            'C4' => ['name' => 'Bahasa Inggris', 'description' => 'Nilai Bahasa Inggris.'],
            'C5' => ['name' => 'Agama', 'description' => 'Nilai Pendidikan Agama.'],
            'C6' => ['name' => 'Matematika', 'description' => 'Nilai Matematika.'],
            'C7' => ['name' => 'Wawancara', 'description' => 'Nilai wawancara calon penerima.'],
            'C8' => ['name' => 'Baca Quran', 'description' => 'Nilai kemampuan membaca Al-Quran.'],
        ];

        foreach ($criteria as $code => $data) {
            $criterion = Criteria::query()->updateOrCreate(
                ['code' => $code],
                [
                    'name' => $data['name'],
                    'type' => 'benefit',
                    'weight' => 0.1250,
                    'description' => $data['description'],
                ]
            );

            foreach ([1, 2, 3, 4, 5] as $score) {
                SubCriteria::query()->updateOrCreate([
                    'criteria_id' => $criterion->id,
                    'score' => $score,
                ], [
                    'name' => "Skor {$score}",
                    'min_value' => $score === 1 ? 0 : ($score - 1) * 20 + 1,
                    'max_value' => $score * 20,
                ]);
            }
        }
    }

    public function down(): void
    {
        Criteria::query()->whereIn('code', ['C7', 'C8'])->delete();

        $weights = [
            'C1' => 0.1667,
            'C2' => 0.1667,
            'C3' => 0.1667,
            'C4' => 0.1667,
            'C5' => 0.1667,
            'C6' => 0.1665,
        ];

        foreach ($weights as $code => $weight) {
            Criteria::query()->where('code', $code)->update(['weight' => $weight]);
        }
    }
};
