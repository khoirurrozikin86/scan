<?php

// app/Support/DataTables/Responder.php
namespace App\Support\DataTables;

use Yajra\DataTables\Facades\DataTables;

class Responder
{
    public static function from($eloquentQuery, callable $map): \Illuminate\Http\JsonResponse
    {
        return DataTables::eloquent($eloquentQuery)
            ->addColumn('actions', fn($row) => $map($row))
            ->rawColumns(['actions'])
            ->toJson();
    }
}
