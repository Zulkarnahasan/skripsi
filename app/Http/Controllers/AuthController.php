<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\StudentProfile;
use App\Models\TestSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        $setting = TestSetting::current();

        return view('auth.login', compact('setting'));
    }

    public function login(Request $request)
    {
        $data = $request->validate(['login' => ['required', 'string'], 'password' => ['required']]);
        $login = $data['login'];
        $email = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? $login
            : StudentProfile::query()->where('nisn', $login)->value('user_id');

        $credentials = [
            'email' => is_numeric($email) ? User::query()->whereKey($email)->value('email') : $email,
            'password' => $data['password'],
        ];

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['login' => 'Email/NISN atau password salah.'])->onlyInput('login');
        }

        if (Auth::user()->role === 'user' && ! Auth::user()->is_active) {
            Auth::logout();

            return back()->withErrors(['login' => 'Akun Anda telah dinonaktifkan oleh admin.'])->onlyInput('login');
        }

        $request->session()->regenerate();

        return redirect()->intended(Auth::user()->role === 'admin' ? '/admin/dashboard' : '/user/dashboard');
    }

    public function showRegister()
    {
        $setting = TestSetting::current();

        return view('auth.register', compact('setting'));
    }

    public function register(Request $request)
    {
        if (! TestSetting::current()->registration_open) {
            return redirect()->route('login')->with('error', 'Pendaftaran akun baru sedang ditutup oleh admin.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'kip_account_number' => ['required', 'string', 'max:50', 'unique:student_profiles,kip_account_number'],
            'school_origin' => ['required', 'string', 'max:150'],
            'entry_year' => ['required', 'integer', 'between:2000,'.(now()->year + 1)],
            'nisn' => ['required', 'string', 'max:30', 'unique:student_profiles,nisn'],
            'npsn' => ['required', 'string', 'max:30'],
            'phone' => ['required', 'string', 'max:30'],
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::query()->create([
                'name' => $data['name'],
                'email' => strtolower($data['nisn']).'@pendaftar.kipk.local',
                'password' => $data['kip_account_number'],
                'role' => 'user',
            ]);

            $profile = $user->studentProfile()->create([
                'kip_account_number' => $data['kip_account_number'],
                'school_origin' => $data['school_origin'],
                'entry_year' => $data['entry_year'],
                'nisn' => $data['nisn'],
                'npsn' => $data['npsn'],
                'phone' => $data['phone'],
                'status' => 'submitted',
            ]);

            Alternative::query()->create([
                'user_id' => $user->id,
                'student_profile_id' => $profile->id,
                'registration_number' => 'KIPK-'.now()->format('Ymd').'-'.str_pad((string) $user->id, 5, '0', STR_PAD_LEFT),
                'status' => 'submitted',
            ]);

            return $user;
        });

        Auth::login($user);

        return redirect('/user/dashboard')->with('success', 'Registrasi berhasil.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
