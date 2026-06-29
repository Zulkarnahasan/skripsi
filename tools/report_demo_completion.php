<?php

use App\Models\Document;
use App\Models\User;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$users = User::query()
    ->where('role', 'user')
    ->with(['studentProfile', 'documents'])
    ->get();

$requiredTypes = Document::requiredTypeKeys();

$completeProfiles = $users->filter(fn (User $user) => $user->hasCompleteProfile())->count();
$completeDocuments = $users->filter(function (User $user) use ($requiredTypes) {
    $uploadedTypes = $user->documents->pluck('document_type')->unique()->all();

    return empty(array_diff($requiredTypes, $uploadedTypes));
})->count();

echo 'User accounts: '.$users->count().PHP_EOL;
echo 'Complete profiles: '.$completeProfiles.PHP_EOL;
echo 'Complete required documents: '.$completeDocuments.PHP_EOL;
echo 'Total documents: '.Document::query()->count().PHP_EOL;
