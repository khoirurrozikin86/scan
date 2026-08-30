<?php

namespace App\Domain\Outlets\Actions;

use App\Domain\Outlets\DTOs\OutletData;
use App\Models\Outlet;
use Illuminate\Support\Facades\DB;

class CreateOutletAction
{
    public function __invoke(OutletData $data): Outlet
    {
        return DB::transaction(
            fn () => Outlet::create($data->toArray())
        );
    }
}