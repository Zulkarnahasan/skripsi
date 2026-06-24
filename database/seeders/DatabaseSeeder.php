<?php

namespace Database\Seeders;

use App\Models\Criteria;
use App\Models\SubCriteria;
use App\Models\TestQuestion;
use App\Models\TestSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::query()->updateOrCreate([
            'email' => 'admin@kipk.test',
        ], [
            'name' => 'Admin KIP-K',
            'password' => 'password',
            'role' => 'admin',
        ]);

        TestSetting::query()->firstOrCreate([], [
            'duration_minutes' => 60,
            'is_open' => false,
            'instruction' => 'Tes sudah dapat dimulai. Kerjakan semua soal sesuai waktu yang ditentukan.',
        ]);

        $criteria = [
            ['C1', 'Bahasa Indonesia', 'benefit', 0.1250, 'Nilai Bahasa Indonesia.', 'Kalimat efektif adalah kalimat yang...', 'Bertele-tele', 'Mudah dipahami dan sesuai kaidah', 'Selalu panjang', 'Tidak memakai tanda baca', 'B'],
            ['C2', 'IPA', 'benefit', 0.1250, 'Nilai Ilmu Pengetahuan Alam.', 'Proses tumbuhan membuat makanan sendiri disebut...', 'Respirasi', 'Fotosintesis', 'Evaporasi', 'Fermentasi', 'B'],
            ['C3', 'IPS', 'benefit', 0.1250, 'Nilai Ilmu Pengetahuan Sosial.', 'Kegiatan menyalurkan barang dari produsen ke konsumen disebut...', 'Produksi', 'Konsumsi', 'Distribusi', 'Investasi', 'C'],
            ['C4', 'Bahasa Inggris', 'benefit', 0.1250, 'Nilai Bahasa Inggris.', 'The correct translation of "Saya belajar setiap hari" is...', 'I study every day', 'I studied tomorrow', 'I am eat daily', 'I learns every day', 'A'],
            ['C5', 'Agama', 'benefit', 0.1250, 'Nilai Pendidikan Agama.', 'Sikap jujur berarti...', 'Mengatakan sesuatu sesuai kenyataan', 'Menghindari tanggung jawab', 'Mengambil hak orang lain', 'Menyembunyikan semua informasi', 'A'],
            ['C6', 'Matematika', 'benefit', 0.1250, 'Nilai Matematika.', 'Hasil dari 12 + 8 adalah...', '18', '20', '22', '24', 'B'],
            ['C7', 'Wawancara', 'benefit', 0.1250, 'Nilai wawancara calon penerima.', null, null, null, null, null, null],
            ['C8', 'Baca Quran', 'benefit', 0.1250, 'Nilai kemampuan membaca Al-Quran.', null, null, null, null, null, null],
        ];

        Criteria::query()
            ->whereNotIn('code', collect($criteria)->pluck(0)->all())
            ->delete();

        foreach ($criteria as [$code, $name, $type, $weight, $description, $question, $optionA, $optionB, $optionC, $optionD, $correctAnswer]) {
            $criterion = Criteria::query()->updateOrCreate(
                ['code' => $code],
                compact('name', 'type', 'weight', 'description')
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

            if ($question) {
                TestQuestion::query()->updateOrCreate([
                    'criteria_id' => $criterion->id,
                    'sort_order' => 1,
                ], [
                    'question' => $question,
                    'option_a' => $optionA,
                    'option_b' => $optionB,
                    'option_c' => $optionC,
                    'option_d' => $optionD,
                    'correct_answer' => $correctAnswer,
                    'is_active' => true,
                ]);
            }
        }
    }
}
