<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\SawResult;
use Illuminate\Http\Request;

class SawResultController extends Controller
{
    public function normalization(Request $request)
    {
        $sort = $request->query('sort', 'tanggal_diubah');
        $sort = in_array($sort, ['abjad', 'ranking', 'tanggal_diubah'], true) ? $sort : 'tanggal_diubah';

        $alternativesQuery = Alternative::query()
            ->search($request->query('q'))
            ->whereHas('scores')
            ->with(['user', 'studentProfile', 'scores.criteria', 'sawResult'])
            ->select('alternatives.*');

        match ($sort) {
            'abjad' => $alternativesQuery
                ->join('users', 'users.id', '=', 'alternatives.user_id')
                ->orderBy('users.name')
                ->orderByDesc('alternatives.updated_at'),
            'ranking' => $alternativesQuery
                ->leftJoin('saw_results', 'saw_results.alternative_id', '=', 'alternatives.id')
                ->orderByRaw('saw_results.rank IS NULL')
                ->orderBy('saw_results.rank')
                ->orderByDesc('alternatives.updated_at'),
            default => $alternativesQuery->latest('alternatives.updated_at'),
        };

        $alternatives = $alternativesQuery
            ->paginate(10)
            ->withQueryString();

        return view('admin.saw.normalization', compact('alternatives', 'sort'));
    }

    public function results()
    {
        $results = SawResult::query()->with('alternative.user')->orderBy('rank')->paginate(20);

        return view('admin.saw.results', compact('results'));
    }

    public function ranking()
    {
        $results = SawResult::query()->with('alternative.user')->orderBy('rank')->get();

        return view('admin.saw.ranking', compact('results'));
    }

    public function announce()
    {
        SawResult::query()->update(['announced_at' => now()]);

        return back()->with('success', 'Hasil seleksi diumumkan.');
    }

    public function updateStatus(Request $request, SawResult $result)
    {
        $data = $request->validate([
            'status' => ['required', 'in:accepted,rejected'],
        ]);

        $result->update($data);

        return back()->with('success', 'Status hasil seleksi diperbarui.');
    }
}
