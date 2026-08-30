<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Super\{RoleController, PermissionController, UserManageController, UserController};
use App\Http\Controllers\Admin\{
    DashboardController,
    OutletController,
    TicketQrcodeController,
    UserOutletController,
    ScanController,
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


        Route::prefix('ticket-qrcode')
            ->name('ticket-qrcode.')
            ->group(function () {

                // VIEW
                Route::middleware('permission:ticket-qrcode.view')
                    ->get('/', [TicketQrcodeController::class, 'index'])
                    ->name('index');

                Route::middleware('permission:ticket-qrcode.view')
                    ->get('/dt', [TicketQrcodeController::class, 'dt'])
                    ->name('dt');

                // CREATE
                Route::middleware('permission:ticket-qrcode.create')
                    ->post('/', [TicketQrcodeController::class, 'store'])
                    ->name('store');

                // UPDATE
                Route::middleware('permission:ticket-qrcode.update')
                    ->put('/{ticketQrcode}', [TicketQrcodeController::class, 'update'])
                    ->name('update');

                // DELETE
                Route::middleware('permission:ticket-qrcode.delete')
                    ->delete('/{ticketQrcode}', [TicketQrcodeController::class, 'destroy'])
                    ->name('destroy');

                // IMPORT = CREATE
                Route::middleware('permission:ticket-qrcode.create')
                    ->post('/import', [TicketQrcodeController::class, 'import'])
                    ->name('import');

                // EXPORT = VIEW
                Route::middleware('permission:ticket-qrcode.view')
                    ->get('/export', [TicketQrcodeController::class, 'export'])
                    ->name('export');
            });



        Route::prefix('user-outlets')
            ->name('user-outlets.')
            ->group(function () {

                // VIEW
                Route::middleware('permission:user-outlets.view')
                    ->get(
                        '/',
                        [UserOutletController::class, 'index']
                    )
                    ->name('index');

                Route::middleware('permission:user-outlets.view')
                    ->get(
                        '/{user}/edit',
                        [UserOutletController::class, 'edit']
                    )
                    ->name('edit');

                // UPDATE
                Route::middleware('permission:user-outlets.update')
                    ->put(
                        '/{user}',
                        [UserOutletController::class, 'update']
                    )
                    ->name('update');
            });



        Route::prefix('scan')
            ->name('scan-records.')
            ->group(function () {

                /*
        |--------------------------------------------------------------------------
        | CAMERA
        |--------------------------------------------------------------------------
        */

                Route::get(
                    '/camera',
                    [ScanController::class, 'camera']
                )
                    ->middleware('permission:scan-records.view')
                    ->name('camera');


                /*
        |--------------------------------------------------------------------------
        | BARCODE SCANNER
        |--------------------------------------------------------------------------
        */

                Route::get(
                    '/scanner',
                    [ScanController::class, 'scanner']
                )
                    ->middleware('permission:scan-records.view')
                    ->name('scanner');


                /*
        |--------------------------------------------------------------------------
        | SCAN RECORD REPORT
        |--------------------------------------------------------------------------
        */

                Route::get(
                    '/records',
                    [ScanController::class, 'index']
                )
                    ->middleware('permission:scan-records.view')
                    ->name('index');


                /*
        |--------------------------------------------------------------------------
        | DATATABLE
        |--------------------------------------------------------------------------
        */

                Route::get(
                    '/records/dt',
                    [ScanController::class, 'dt']
                )
                    ->middleware('permission:scan-records.view')
                    ->name('dt');


                /*
        |--------------------------------------------------------------------------
        | PROCESS SCAN
        |--------------------------------------------------------------------------
        */

                Route::post(
                    '/scan',
                    [ScanController::class, 'scan']
                )
                    ->middleware('permission:scan-records.create')
                    ->name('scan');


                /*
        |--------------------------------------------------------------------------
        | HISTORY 10 TERAKHIR
        |--------------------------------------------------------------------------
        */

                Route::get(
                    '/history',
                    [ScanController::class, 'history']
                )
                    ->middleware('permission:scan-records.view')
                    ->name('history');


                /*
        |--------------------------------------------------------------------------
        | DELETE SCAN RECORD
        |--------------------------------------------------------------------------
        */

                Route::delete(
                    '/records/{scanRecord}',
                    [ScanController::class, 'destroy']
                )
                    ->middleware('permission:scan-records.delete')
                    ->name('destroy');


                Route::get(
                    '/records/export',
                    [ScanController::class, 'export']
                )
                    ->middleware('permission:scan-records.view')
                    ->name('export');
            });
    });
