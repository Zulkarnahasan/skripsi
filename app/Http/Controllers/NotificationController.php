<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function userIndex(Request $request)
    {
        $notifications = $request->user()->notifications()->latest()->paginate(10);
        $request->user()->notifications()->where('is_read', false)->update(['is_read' => true]);

        return view('user.notifications', compact('notifications'));
    }

    public function adminIndex()
    {
        $users = User::query()->where('role', 'user')->orderBy('name')->get();
        $notifications = Notification::query()->with('user')->latest()->paginate(10);

        return view('admin.notifications.index', compact('users', 'notifications'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'title' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string'],
        ]);

        if (empty($data['user_id'])) {
            User::query()->where('role', 'user')->each(fn ($user) => $user->notifications()->create($data));
        } else {
            Notification::query()->create($data);
        }

        return back()->with('success', 'Notifikasi terkirim.');
    }
}
