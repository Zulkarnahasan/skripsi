<?php

namespace App\Http\Controllers;

use App\Models\TestSetting;
use App\Models\User;
use Illuminate\Http\Request;

class AdminRegistrationController extends Controller
{
    public function edit()
    {
        $setting = TestSetting::current();
        $userCount = User::query()->where('role', 'user')->count();

        return view('admin.registration.edit', compact('setting', 'userCount'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'registration_open' => ['required', 'boolean'],
        ]);

        TestSetting::current()->update([
            'registration_open' => (bool) $data['registration_open'],
        ]);

        return back()->with('success', $data['registration_open'] ? 'Pendaftaran berhasil dibuka.' : 'Pendaftaran berhasil ditutup.');
    }
}
