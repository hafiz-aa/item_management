<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemDescriptionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware(['can:item.view'])->group(function () {
        Route::resource('items', ItemController::class);
        Route::post('items/bulk-delete', [ItemController::class, 'bulkDelete'])->name('items.bulk-delete')
            ->middleware('can:item.delete');
    });

    Route::middleware(['can:warehouse.manage'])->group(function () {
        Route::resource('warehouses', WarehouseController::class)->except('show');
    });

    Route::middleware(['can:branch.view'])->group(function () {
        Route::resource('branches', BranchController::class)->except('show');
        Route::post('branches/{id}/restore', [BranchController::class, 'restore'])
            ->name('branches.restore')
            ->middleware('can:branch.delete');
    });

    Route::middleware(['can:item-category.view'])->group(function () {
        Route::resource('item-categories', ItemCategoryController::class)->except('show');
        Route::post('item-categories/{id}/restore', [ItemCategoryController::class, 'restore'])
            ->name('item-categories.restore')
            ->middleware('can:item-category.delete');
    });

    Route::middleware(['can:item-description.view'])->group(function () {
        Route::resource('item-descriptions', ItemDescriptionController::class)->except('show');
        Route::post('item-descriptions/{id}/restore', [ItemDescriptionController::class, 'restore'])
            ->name('item-descriptions.restore')
            ->middleware('can:item-description.delete');
    });

    Route::middleware(['can:user.manage'])->group(function () {
        Route::resource('users', UserController::class)->except('show');
    });

    Route::middleware(['can:role.manage'])->group(function () {
        Route::resource('roles', RoleController::class)->except('show');
    });

    Route::middleware(['can:permission.manage'])->group(function () {
        Route::resource('permissions', PermissionController::class)->except('show');
    });

    Route::middleware(['can:activity-log.view'])->group(function () {
        Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    Route::middleware(['can:item.export'])->group(function () {
        Route::get('export/items', [ExportController::class, 'export'])->name('export.items');
        Route::get('export/template', [ExportController::class, 'template'])->name('export.template');
    });

    Route::middleware(['can:item.import'])->group(function () {
        Route::get('import', [ImportController::class, 'create'])->name('import.create');
        Route::post('import', [ImportController::class, 'import'])->name('import.process');
        Route::get('import/result', [ImportController::class, 'result'])->name('import.result');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
