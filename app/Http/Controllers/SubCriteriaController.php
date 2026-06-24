<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\SubCriteria;
use Illuminate\Http\Request;

class SubCriteriaController extends Controller
{
    public function index()
    {
        $subCriteria = SubCriteria::query()->with('criteria')->latest()->paginate(10);

        return view('admin.sub-criteria.index', compact('subCriteria'));
    }

    public function create()
    {
        $criteria = Criteria::query()->orderBy('code')->get();

        return view('admin.sub-criteria.create', compact('criteria'));
    }

    public function store(Request $request)
    {
        SubCriteria::query()->create($this->validated($request));

        return redirect()->route('sub-criteria.index')->with('success', 'Sub-kriteria ditambahkan.');
    }

    public function edit(SubCriteria $subCriterion)
    {
        $criteria = Criteria::query()->orderBy('code')->get();

        return view('admin.sub-criteria.edit', compact('subCriterion', 'criteria'));
    }

    public function update(Request $request, SubCriteria $subCriterion)
    {
        $subCriterion->update($this->validated($request));

        return redirect()->route('sub-criteria.index')->with('success', 'Sub-kriteria diperbarui.');
    }

    public function destroy(SubCriteria $subCriterion)
    {
        $subCriterion->delete();

        return back()->with('success', 'Sub-kriteria dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'criteria_id' => ['required', 'exists:criteria,id'],
            'name' => ['required', 'max:150'],
            'min_value' => ['nullable', 'numeric'],
            'max_value' => ['nullable', 'numeric'],
            'score' => ['required', 'numeric', 'min:0'],
        ]);
    }
}
