<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $documents = $request->user()->documents()->latest()->get();

        return view('user.documents', compact('documents'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'document_type' => ['required', 'string', 'max:100'],
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'],
        ]);

        $request->user()->documents()->create([
            'student_profile_id' => $request->user()->studentProfile?->id,
            'document_type' => $data['document_type'],
            'file_path' => $request->file('file')->store('documents', 'public'),
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah.');
    }
}
