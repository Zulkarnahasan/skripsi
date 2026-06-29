<?php

use App\Models\Alternative;
use App\Models\Document;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Storage;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$publicDisk = Storage::disk('public');

$pdfPath = 'documents/demo-complete-document.pdf';
$profilePhotoPath = 'profile-photos/demo-profile-photo.png';

if (! $publicDisk->exists($pdfPath)) {
    $publicDisk->put($pdfPath, "%PDF-1.4\n1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Contents 4 0 R >>\nendobj\n4 0 obj\n<< /Length 58 >>\nstream\nBT /F1 18 Tf 72 720 Td (Dokumen demo KIP-K lengkap) Tj ET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f \n0000000009 00000 n \n0000000058 00000 n \n0000000115 00000 n \n0000000207 00000 n \ntrailer\n<< /Root 1 0 R /Size 5 >>\nstartxref\n315\n%%EOF\n");
}

if (! $publicDisk->exists($profilePhotoPath)) {
    $sourceLogo = public_path('images/permakip-logo.png');

    if (is_file($sourceLogo)) {
        $publicDisk->put($profilePhotoPath, file_get_contents($sourceLogo));
    }
}

$programs = config('umt_programs');
$firstProgramKey = 'teknik-informatika-s1';
$secondProgramKey = 'manajemen-s1';

$users = User::query()
    ->where('role', 'user')
    ->orderBy('id')
    ->get();

$completed = 0;

foreach ($users as $user) {
    $user->update(['password' => 'password']);

    $profile = $user->studentProfile()->updateOrCreate(
        ['user_id' => $user->id],
        [
            'birth_date' => '2006-01-'.str_pad((string) (($user->id % 27) + 1), 2, '0', STR_PAD_LEFT),
            'gender' => $user->id % 2 === 0 ? 'P' : 'L',
            'kip_account_number' => 'KIP-DEMO-'.str_pad((string) $user->id, 6, '0', STR_PAD_LEFT),
            'school_origin' => 'SMA Demo Lengkap',
            'study_program' => $firstProgramKey,
            'study_program_accreditation' => $programs[$firstProgramKey]['accreditation'] ?? 'Unggul',
            'study_program_2' => $secondProgramKey,
            'study_program_accreditation_2' => $programs[$secondProgramKey]['accreditation'] ?? 'Unggul',
            'entry_year' => 2026,
            'graduation_year' => 2025,
            'nisn' => '900'.str_pad((string) $user->id, 7, '0', STR_PAD_LEFT),
            'npsn' => '700'.str_pad((string) $user->id, 5, '0', STR_PAD_LEFT),
            'address' => 'Alamat demo lengkap untuk melihat tampilan profil selesai.',
            'phone' => '0812'.str_pad((string) $user->id, 8, '0', STR_PAD_LEFT),
            'profile_photo_path' => $profilePhotoPath,
            'status' => 'verified',
        ]
    );

    Alternative::query()->updateOrCreate(
        ['user_id' => $user->id],
        [
            'student_profile_id' => $profile->id,
            'registration_number' => 'KIPK-20260629-'.str_pad((string) $user->id, 5, '0', STR_PAD_LEFT),
            'status' => 'verified',
        ]
    );

    foreach (Document::typeKeys() as $documentType) {
        $user->documents()->updateOrCreate(
            ['document_type' => $documentType],
            [
                'student_profile_id' => $profile->id,
                'file_path' => $pdfPath,
                'status' => 'verified',
            ]
        );
    }

    $completed++;
}

echo "Completed {$completed} user account(s).\n";
