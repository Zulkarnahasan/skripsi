<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $documents = $request->user()->documents()->latest()->get()->keyBy('document_type');
        $documentTypes = Document::TYPES;

        return view('user.documents', compact('documents', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'document_type' => ['required', 'string', Rule::in(Document::typeKeys())],
            'file' => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $existingDocument = $request->user()->documents()
            ->where('document_type', $data['document_type'])
            ->first();

        if ($existingDocument) {
            Storage::disk('public')->delete($existingDocument->file_path);
        }

        $request->user()->documents()->updateOrCreate([
            'document_type' => $data['document_type'],
        ], [
            'student_profile_id' => $request->user()->studentProfile?->id,
            'file_path' => $request->file('file')->store('documents', 'public'),
            'status' => 'pending',
        ]);

        return back()->with('success', 'Dokumen berhasil diunggah dan menunggu verifikasi admin.');
    }
}
