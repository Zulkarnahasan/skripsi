<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $entryYears = User::query()
            ->where('role', 'user')
            ->join('student_profiles', 'student_profiles.user_id', '=', 'users.id')
            ->whereNotNull('student_profiles.entry_year')
            ->distinct()
            ->orderByDesc('student_profiles.entry_year')
            ->pluck('student_profiles.entry_year');

        $users = User::query()
            ->with('studentProfile')
            ->where('role', 'user')
            ->search($request->query('q'))
            ->when($request->integer('entry_year'), fn ($q, $year) => $q
                ->whereHas('studentProfile', fn ($profile) => $profile->where('entry_year', $year)))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.users.index', compact('users', 'entryYears'));
    }

    public function deactivate(User $user)
    {
        abort_if($user->role !== 'user', 403);

        $user->update(['is_active' => false]);

        return back()->with('success', 'Akun user berhasil dinonaktifkan.');
    }

    public function activate(User $user)
    {
        abort_if($user->role !== 'user', 403);

        $user->update(['is_active' => true]);

        return back()->with('success', 'Akun user berhasil diaktifkan kembali.');
    }

    public function destroy(User $user)
    {
        abort_if($user->role !== 'user', 403);

        $user->delete();

        return back()->with('success', 'Akun user berhasil dihapus.');
    }
}
