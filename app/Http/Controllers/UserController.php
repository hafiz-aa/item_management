<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\WarehouseService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    private UserRepository $userRepository;
    private WarehouseService $warehouseService;

    public function __construct(UserRepository $userRepository, WarehouseService $warehouseService)
    {
        $this->userRepository = $userRepository;
        $this->warehouseService = $warehouseService;
    }

    public function index(): View
    {
        $users = User::with('roles', 'warehouses')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create(): View
    {
        $roles = Role::all();
        $warehouses = $this->warehouseService->getActive();
        return view('users.create', compact('roles', 'warehouses'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $this->userRepository->createWithRoles([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'is_active' => true,
        ], $data['roles']);

        if (!empty($data['warehouses'])) {
            $user->warehouses()->sync($data['warehouses']);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $roles = Role::all();
        $warehouses = $this->warehouseService->getActive();
        $user->load('warehouses');
        return view('users.edit', compact('user', 'roles', 'warehouses'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = bcrypt($data['password']);
        }

        $this->userRepository->updateWithRoles($user, $updateData, $data['roles']);

        if (isset($data['warehouses'])) {
            $user->warehouses()->sync($data['warehouses']);
        }

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diupdate.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->hasRole('Super Admin')) {
            return redirect()->route('users.index')
                ->with('error', 'Super Admin tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
