<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\SawResult;
use App\Models\TestAnswer;
use App\Models\TestQuestion;
use App\Models\TestSetting;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $setting = TestSetting::current();
        $answeredUsers = TestAnswer::query()->distinct('alternative_id')->count('alternative_id');

        $stats = [
            'Total User' => User::query()->where('role', 'user')->count(),
            'Terverifikasi' => Alternative::query()->where('status', 'verified')->count(),
            'Sudah Tes' => $answeredUsers,
            'Soal Aktif' => TestQuestion::query()->where('is_active', true)->count(),
            'Kuota Lulus' => $setting->selection_quota,
            'Lulus' => SawResult::query()->where('status', 'accepted')->count(),
            'Tidak Lulus' => SawResult::query()->where('status', 'rejected')->count(),
        ];

        $summary = [
            'answered_percent' => $stats['Total User'] > 0 ? round(($answeredUsers / $stats['Total User']) * 100) : 0,
            'quota_remaining' => max((int) $setting->selection_quota - (int) $stats['Lulus'], 0),
            'processed' => SawResult::query()->count(),
        ];

        return view('admin.dashboard', compact('stats', 'summary'));
    }
}
