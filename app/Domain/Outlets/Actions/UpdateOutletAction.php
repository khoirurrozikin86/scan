<?php

namespace App\Domain\Outlets\Actions;

use App\Domain\Outlets\DTOs\OutletData;
use App\Models\Outlet;
use Illuminate\Support\Facades\DB;

class UpdateOutletAction
{
    public function __invoke(
        Outlet $outlet,
        OutletData $data
    ): Outlet {
        return DB::transaction(function () use ($outlet, $data) {

            $outlet->update(
                $data->toArray()
            );

            return $outlet->refresh();
        });
    }
}