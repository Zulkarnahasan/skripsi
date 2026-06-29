<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\Document;
use Illuminate\Http\Request;

class AdminApplicantController extends Controller
{
    public function index(Request $request)
    {
        $entryYears = Alternative::query()
            ->join('student_profiles', 'student_profiles.id', '=', 'alternatives.student_profile_id')
            ->whereNotNull('student_profiles.entry_year')
            ->distinct()
            ->orderByDesc('student_profiles.entry_year')
            ->pluck('student_profiles.entry_year');

        $applicants = Alternative::query()
            ->with(['user', 'studentProfile', 'sawResult'])
            ->search($request->query('q'))
            ->when($request->query('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->integer('entry_year'), fn ($q, $year) => $q
                ->whereHas('studentProfile', fn ($profile) => $profile->where('entry_year', $year)))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.applicants.index', compact('applicants', 'entryYears'));
    }

    public function show(Alternative $applicant)
    {
        $applicant->load(['user.documents', 'studentProfile', 'scores.criteria', 'sawResult']);
        $documents = $applicant->user->documents->keyBy('document_type');
        $documentTypes = Document::TYPES;

        return view('admin.applicants.show', compact('applicant', 'documents', 'documentTypes'));
    }

    public function updateStatus(Request $request, Alternative $applicant)
    {
        $data = $request->validate(['status' => ['required', 'in:verified,rejected']]);
        $applicant->update($data);
        $applicant->studentProfile()->update(['status' => $data['status']]);
        $applicant->user->notifications()->create([
            'title' => 'Status pendaftaran diperbarui',
            'message' => 'Status pendaftaran Anda: '.$data['status'],
        ]);

        return $request->expectsJson() ? response()->json(['ok' => true, 'status' => $data['status']]) : back()->with('success', 'Status diperbarui.');
    }

    public function updateDocumentStatus(Request $request, Alternative $applicant, Document $document)
    {
        abort_unless($document->user_id === $applicant->user_id, 404);

        $data = $request->validate(['status' => ['required', 'in:verified,rejected']]);

        $document->update(['status' => $data['status']]);

        $statusLabels = [
            'verified' => 'terverifikasi',
            'rejected' => 'ditolak',
        ];

        $applicant->user->notifications()->create([
            'title' => 'Status dokumen diperbarui',
            'message' => Document::typeLabel($document->document_type).' '.$statusLabels[$data['status']].'.',
        ]);

        return back()->with('success', 'Status dokumen berhasil diperbarui.');
    }
}
