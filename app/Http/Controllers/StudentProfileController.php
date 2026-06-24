<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    public function edit(Request $request)
    {
        $profile = $request->user()->studentProfile;
        $user = $request->user();

        return view('user.profile', compact('profile', 'user'));
    }

    public function update(Request $request)
    {
        $userId = $request->user()->id;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'kip_account_number' => ['required', 'string', 'max:50', 'unique:student_profiles,kip_account_number,'.$userId.',user_id'],
            'school_origin' => ['required', 'string', 'max:150'],
            'entry_year' => ['required', 'integer', 'between:2000,'.(now()->year + 1)],
            'nisn' => ['required', 'string', 'max:30', 'unique:student_profiles,nisn,'.$userId.',user_id'],
            'npsn' => ['required', 'string', 'max:30'],
            'phone' => ['required', 'string', 'max:30'],
        ]);

        $request->user()->update(['name' => $data['name']]);

        $profile = $request->user()->studentProfile()->updateOrCreate(
            ['user_id' => $userId],
            [
                'kip_account_number' => $data['kip_account_number'],
                'school_origin' => $data['school_origin'],
                'entry_year' => $data['entry_year'],
                'nisn' => $data['nisn'],
                'npsn' => $data['npsn'],
                'phone' => $data['phone'],
                'status' => 'submitted',
            ]
        );

        Alternative::query()->updateOrCreate(
            ['user_id' => $userId],
            [
                'student_profile_id' => $profile->id,
                'registration_number' => 'KIPK-'.now()->format('Ymd').'-'.str_pad((string) $userId, 5, '0', STR_PAD_LEFT),
                'status' => 'submitted',
            ]
        );

        return back()->with('success', 'Profil pendaftaran tersimpan.');
    }

    public function test(Request $request)
    {
        $criteria = Criteria::query()->orderBy('code')->get();
        $alternative = $request->user()->alternative;

        return view('user.test', compact('criteria', 'alternative'));
    }
}
