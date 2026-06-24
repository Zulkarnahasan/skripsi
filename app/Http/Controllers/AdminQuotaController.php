<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\TestSetting;
use App\Services\SawCalculationService;
use Illuminate\Http\Request;

class AdminQuotaController extends Controller
{
    public function edit()
    {
        $setting = TestSetting::current();
        $verifiedCount = Alternative::query()->where('status', 'verified')->count();

        return view('admin.quota.edit', compact('setting', 'verifiedCount'));
    }

    public function update(Request $request, SawCalculationService $sawCalculation)
    {
        $data = $request->validate([
            'selection_quota' => ['required', 'integer', 'min:1', 'max:10000'],
        ]);

        $setting = TestSetting::current();
        $setting->update($data);

        $processed = $sawCalculation->process((int) $setting->selection_quota);

        return back()->with('success', 'Kuota lulus diperbarui dan hasil SAW dihitung ulang untuk '.$processed['processed'].' pendaftar.');
    }
}
