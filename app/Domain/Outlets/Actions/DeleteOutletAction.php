<?php

namespace App\Domain\Outlets\Actions;

use App\Models\Outlet;
use Illuminate\Support\Facades\DB;

class DeleteOutletAction
{
    public function __invoke(Outlet $outlet): void
    {
        DB::transaction(
            fn () => $outlet->delete()
        );
    }
}