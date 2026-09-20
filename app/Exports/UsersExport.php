<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::query()
            ->with('roles')
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Name',
            'Email',
            'Roles',
        ];
    }

    public function map($user): array
    {
        return [
            $user->name ?? '-',
            $user->email ?? '-',
            $user->roles
                ->pluck('name')
                ->implode(', ') ?: '-',
        ];
    }
}
