<?php

namespace App\Domain\Outlets\Services;

use App\Domain\Outlets\Actions\{
    CreateOutletAction,
    UpdateOutletAction,
    DeleteOutletAction
};

use App\Domain\Outlets\DTOs\OutletData;
use App\Models\Outlet;

class OutletService
{
    public function __construct(
        protected CreateOutletAction $create,
        protected UpdateOutletAction $update,
        protected DeleteOutletAction $delete,
    ) {}

    public function create(array $payload): Outlet
    {
        return ($this->create)(
            OutletData::fromArray($payload)
        );
    }

    public function update(
        Outlet $outlet,
        array $payload
    ): Outlet {
        return ($this->update)(
            $outlet,
            OutletData::fromArray($payload)
        );
    }

    public function delete(Outlet $outlet): void
    {
        ($this->delete)($outlet);
    }
}