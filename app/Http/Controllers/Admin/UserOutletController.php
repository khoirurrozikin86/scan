<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserOutletStoreRequest;
use App\Domain\UserOutlets\Services\UserOutletService;
use App\Models\User;
use App\Models\Outlet;

class UserOutletController extends Controller
{
    public function index()
    {
        $users = User::query()
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'email',
            ]);

        $outlets = Outlet::query()
            ->where('is_active', 1)
            ->select([
                'id',
                'outlet_code',
                'outlet_name',
                'outlet_type',
            ])
            ->orderBy('outlet_name')
            ->get();


        return view(
            'super.user-outlets.index',
            compact(
                'users',
                'outlets'
            )
        );
    }

    public function edit(User $user)
    {
        return response()->json([
            'id' => $user->id,

            'outlet_ids' => $user
                ->outlets()
                ->pluck('outlets.id')
                ->values()
                ->all(),
        ]);
    }

    public function update(
        UserOutletStoreRequest $request,
        User $user,
        UserOutletService $service
    ) {
        $service->sync(
            $user,
            $request->sanitized()
        );

        return $request->ajax() ||
            $request->expectsJson()

            ? response()->json([
                'message' =>
                'User outlet access updated',
            ])

            : back()->with(
                'success',
                'User outlet access updated'
            );
    }
}
