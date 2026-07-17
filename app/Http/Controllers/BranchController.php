<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Services\BranchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    private BranchService $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'per_page']);
        $branches = $this->branchService->search($filters);

        return view('branches.index', compact('branches', 'filters'));
    }

    public function create(): View
    {
        return view('branches.create');
    }

    public function store(StoreBranchRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['branch_id'] = Branch::max('branch_id') + 1;
        $data['comp_id'] = $data['comp_id'] ?? 0;

        $this->branchService->create($data);

        return redirect()->route('branches.index')
            ->with('success', 'Branch berhasil ditambahkan.');
    }

    public function edit(Branch $branch): View
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(UpdateBranchRequest $request, Branch $branch): RedirectResponse
    {
        $this->branchService->update($branch, $request->validated());

        return redirect()->route('branches.index')
            ->with('success', 'Branch berhasil diupdate.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $this->branchService->delete($branch);

        return redirect()->route('branches.index')
            ->with('success', 'Branch berhasil dihapus.');
    }
}
