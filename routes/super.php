<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Super\{RoleController, PermissionController, UserManageController, UserController};
use App\Http\Controllers\Admin\{



    CategoryController,
    TicketController,
    DashboardController,
    OutletController,
    TicketQrcodeController,
};

Route::middleware(['auth'])
    ->prefix('super')->name('super.')->group(function () {


        Route::get(
            '/',
            [DashboardController::class, 'index']
        )
            ->name('dashboard')
            ->middleware('permission:dashboard.view');

        /* ===== Access Control ===== */

        // ROLES
        Route::middleware('permission:role.read')->get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::middleware('permission:role.read')->get('/roles/dt', [RoleController::class, 'datatable'])->name('roles.dt');
        Route::middleware('permission:role.create')->get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::middleware('permission:role.create')->post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::middleware('permission:role.update')->get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::middleware('permission:role.update')->put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::middleware('permission:role.delete')->delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
        Route::middleware('permission:role.update')->get('/roles/{role}/permissions', [RoleController::class, 'editPermissions'])->name('roles.permissions.edit');
        Route::middleware('permission:role.update')->put('/roles/{role}/permissions', [RoleController::class, 'updatePermissions'])->name('roles.permissions.update');

        // PERMISSIONS
        Route::middleware('permission:permission.read')->get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::middleware('permission:permission.read')->get('/permissions/dt', [PermissionController::class, 'datatable'])->name('permissions.dt');
        Route::middleware('permission:permission.create')->get('/permissions/create', [PermissionController::class, 'create'])->name('permissions.create');
        Route::middleware('permission:permission.create')->post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
        Route::middleware('permission:permission.update')->get('/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit');
        Route::middleware('permission:permission.update')->put('/permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update');
        Route::middleware('permission:permission.delete')->delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy');

        // USERS (manajemen user)
        Route::prefix('users')->name('user.')->group(function () {
            Route::middleware('permission:user.read')->get('/', [UserController::class, 'index'])->name('index');
            Route::middleware('permission:user.read')->get('/dt', [UserController::class, 'datatable'])->name('dt');
            Route::middleware('permission:user.create')->get('/create', [UserController::class, 'create'])->name('create');
            Route::middleware('permission:user.create')->post('/', [UserController::class, 'store'])->name('store');
            Route::middleware('permission:user.update')->get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
            Route::middleware('permission:user.update')->put('/{user}', [UserController::class, 'update'])->name('update');
            Route::middleware('permission:user.delete')->delete('/{user}', [UserController::class, 'destroy'])->name('destroy');

            Route::middleware('permission:user.update')->put('/{user}/roles', [UserManageController::class, 'syncRoles'])->name('roles.sync');
            Route::middleware('permission:user.update')->put('/{user}/perms', [UserManageController::class, 'syncPermissions'])->name('perms.sync');
        });

        /* ===== Settings ===== */



        // CATEGORIES
        // Route::middleware('permission:categories.view')
        //     ->get('categories', [CategoryController::class, 'index'])
        //     ->name('categories.index');

        // Route::middleware('permission:categories.view')
        //     ->get('categories/dt', [CategoryController::class, 'dt'])
        //     ->name('categories.dt');

        // Route::middleware('permission:categories.view')
        //     ->get('categories/export/xlsx', [CategoryController::class, 'export'])
        //     ->name('categories.export');

        // Route::middleware('permission:categories.create')
        //     ->post('categories', [CategoryController::class, 'store'])
        //     ->name('categories.store');

        // Route::middleware('permission:categories.update')
        //     ->put('categories/{category}', [CategoryController::class, 'update'])
        //     ->name('categories.update');

        // Route::middleware('permission:categories.delete')
        //     ->delete('categories/{category}', [CategoryController::class, 'destroy'])
        //     ->name('categories.destroy');




        // TICKETS
        // Route::middleware('permission:tickets.view')
        //     ->get('tickets', [TicketController::class, 'index'])
        //     ->name('tickets.index');

        // Route::middleware('permission:tickets.view')
        //     ->get('tickets/dt', [TicketController::class, 'dt'])
        //     ->name('tickets.dt');

        // Route::middleware('permission:tickets.view')
        //     ->get('tickets/export/xlsx', [TicketController::class, 'export'])
        //     ->name('tickets.export');

        // Route::middleware('permission:tickets.create')
        //     ->post('tickets', [TicketController::class, 'store'])
        //     ->name('tickets.store');

        // Route::middleware('permission:tickets.update')
        //     ->put('tickets/{ticket}', [TicketController::class, 'update'])
        //     ->name('tickets.update');

        // Route::middleware('permission:tickets.delete')
        //     ->delete('tickets/{ticket}', [TicketController::class, 'destroy'])
        //     ->name('tickets.destroy');




        // OUTLET
        Route::middleware('permission:outlets.view')
            ->get('outlets', [OutletController::class, 'index'])
            ->name('outlets.index');

        Route::middleware('permission:outlets.view')
            ->get('outlets/dt', [OutletController::class, 'dt'])
            ->name('outlets.dt');


        Route::middleware('permission:outlets.update')
            ->put('outlets/{outlet}', [OutletController::class, 'update'])
            ->name('outlets.update');

        Route::middleware('permission:outlets.delete')
            ->delete('outlets/{outlet}', [OutletController::class, 'destroy'])
            ->name('outlets.destroy');


        Route::middleware('permission:outlets.create')
            ->post('outlets', [OutletController::class, 'store'])
            ->name('outlets.store');


        Route::middleware('permission:outlets.view')
            ->get('outlets/export/xlsx', [OutletController::class, 'export'])
            ->name('outlets.export');



        Route::prefix('ticket-qrcodes')
            ->name('ticket-qrcodes.')
            ->group(function () {

                Route::get(
                    '/',
                    [TicketQrcodeController::class, 'index']
                )->name('index');

                Route::get(
                    '/dt',
                    [TicketQrcodeController::class, 'dt']
                )->name('dt');

                Route::post(
                    '/',
                    [TicketQrcodeController::class, 'store']
                )->name('store');

                Route::put(
                    '/{ticketQrcode}',
                    [TicketQrcodeController::class, 'update']
                )->name('update');

                Route::delete(
                    '/{ticketQrcode}',
                    [TicketQrcodeController::class, 'destroy']
                )->name('destroy');

                Route::post(
                    '/import',
                    [TicketQrcodeController::class, 'import']
                )->name('import');
            });








        Route::middleware('permission:tickets.update')
            ->put(
                'tickets/{ticket}/status',
                [TicketController::class, 'updateStatus']
            )
            ->name('tickets.status');
    });
