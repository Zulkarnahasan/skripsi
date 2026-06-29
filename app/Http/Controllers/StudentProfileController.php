<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Criteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentProfileController extends Controller
{
    public function edit(Request $request)
    {
        $profile = $request->user()->studentProfile;
        $user = $request->user();
        $programs = config('umt_programs');

        return view('user.profile', compact('profile', 'user', 'programs'));
    }

    public function update(Request $request)
    {
        $userId = $request->user()->id;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'kip_account_number' => ['required', 'string', 'max:50', 'unique:student_profiles,kip_account_number,'.$userId.',user_id'],
            'school_origin' => ['required', 'string', 'max:150'],
            'study_program' => ['required', 'string', Rule::in(array_keys(config('umt_programs')))],
            'study_program_2' => ['required', 'string', 'different:study_program', Rule::in(array_keys(config('umt_programs')))],
            'entry_year' => ['required', 'integer', 'between:2000,'.(now()->year + 1)],
            'graduation_year' => ['required', 'integer', 'between:2000,'.(now()->year + 1)],
            'nisn' => ['required', 'string', 'max:30', 'unique:student_profiles,nisn,'.$userId.',user_id'],
            'npsn' => ['required', 'string', 'max:30'],
            'phone' => ['required', 'string', 'max:30'],
            'profile_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:1024'],
        ]);

        $request->user()->update(['name' => $data['name']]);

        $program = config("umt_programs.{$data['study_program']}");
        $program2 = config("umt_programs.{$data['study_program_2']}");

        $profilePayload = [
            'kip_account_number' => $data['kip_account_number'],
            'school_origin' => $data['school_origin'],
            'study_program' => "{$program['level']} {$program['name']}",
            'study_program_accreditation' => $program['accreditation'],
            'study_program_2' => "{$program2['level']} {$program2['name']}",
            'study_program_accreditation_2' => $program2['accreditation'],
            'entry_year' => $data['entry_year'],
            'graduation_year' => $data['graduation_year'],
            'nisn' => $data['nisn'],
            'npsn' => $data['npsn'],
            'phone' => $data['phone'],
            'status' => 'submitted',
        ];

        if ($request->hasFile('profile_photo')) {
            $oldPhoto = $request->user()->studentProfile?->profile_photo_path;

            if ($oldPhoto) {
                Storage::disk('public')->delete($oldPhoto);
            }

            $profilePayload['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $profile = $request->user()->studentProfile()->updateOrCreate(
            ['user_id' => $userId],
            $profilePayload
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
