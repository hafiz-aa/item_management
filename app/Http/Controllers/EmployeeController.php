<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmployeeController extends Controller
{
    public function index(Request $request): View
    {
        $query = Employee::query()
            ->leftJoin('branch', 'branch.branch_id', '=', 'employee.branch_id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('employee.emp_code', 'like', "%{$search}%")
                    ->orWhere('employee.emp_name', 'like', "%{$search}%")
                    ->orWhere('employee.emp_email', 'like', "%{$search}%")
                    ->orWhere('employee.emp_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('branch_id')) {
            $query->where('employee.branch_id', $request->branch_id);
        }

        $employees = $query->orderBy('employee.emp_code', 'asc')
            ->paginate($request->get('per_page', 25))
            ->withQueryString();

        $branches = Branch::orderBy('branch_code')->get();
        $filters = $request->only(['search', 'branch_id']);

        return view('settings.employee', compact('employees', 'branches', 'filters'));
    }

    public function create(): View
    {
        $branches = Branch::orderBy('branch_code')->get();

        return view('settings.create-employee', compact('branches'));
    }

    public function store(StoreEmployeeRequest $request): RedirectResponse
    {
        Employee::create($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee berhasil ditambahkan.');
    }

    public function edit(Employee $employee): View
    {
        $branches = Branch::orderBy('branch_code')->get();

        return view('settings.edit-employee', compact('employee', 'branches'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee): RedirectResponse
    {
        $employee->update($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee berhasil diupdate.');
    }

    public function destroy(Employee $employee): RedirectResponse
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee berhasil dihapus.');
    }
}
