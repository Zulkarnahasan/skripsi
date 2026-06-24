<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    public function index(Request $request)
    {
        $criteria = Criteria::query()->search($request->query('q'))->orderBy('code')->paginate(10);

        return view('admin.criteria.index', compact('criteria'));
    }

    public function create()
    {
        return view('admin.criteria.create');
    }

    public function store(Request $request)
    {
        Criteria::query()->create($this->validated($request));

        return redirect()->route('criteria.index')->with('success', 'Kriteria ditambahkan.');
    }

    public function edit(Criteria $criterion)
    {
        return view('admin.criteria.edit', compact('criterion'));
    }

    public function update(Request $request, Criteria $criterion)
    {
        $criterion->update($this->validated($request, $criterion->id));

        return redirect()->route('criteria.index')->with('success', 'Kriteria diperbarui.');
    }

    public function destroy(Criteria $criterion)
    {
        $criterion->delete();

        return back()->with('success', 'Kriteria dihapus.');
    }

    private function validated(Request $request, ?int $id = null): array
    {
        $data = $request->validate([
            'code' => ['required', 'max:20', 'unique:criteria,code,'.($id ?: 'NULL')],
            'name' => ['required', 'max:150'],
            'type' => ['required', 'in:benefit,cost'],
            'weight' => ['required', 'numeric', 'min:0', 'max:100'],
            'description' => ['nullable', 'string'],
        ]);

        $data['weight'] = round(((float) $data['weight']) / 100, 4);

        return $data;
    }
}
