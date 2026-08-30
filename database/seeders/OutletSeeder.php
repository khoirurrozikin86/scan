<?php

namespace Database\Seeders;

use App\Models\Outlet;
use Illuminate\Database\Seeder;

class OutletSeeder extends Seeder
{
    /**
     * Seed the outlets table.
     */
    public function run(): void
    {
        $outlets = [
            // =========================================================
            // ADMIN
            // =========================================================

            [
                'outlet_code' => 'DSO-SUPERADMIN',
                'outlet_name' => 'Super Admin',
                'outlet_type' => 'Superadmin',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSO-ADM-WAH',
                'outlet_name' => 'Admin Wahana',
                'outlet_type' => 'Admin',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-ADM-FIN',
                'outlet_name' => 'Admin Finance',
                'outlet_type' => 'Admin',
                'is_active'   => true,
            ],

            // =========================================================
            // TEST
            // =========================================================

            [
                'outlet_code' => 'DSW-OTEST',
                'outlet_name' => 'Outlet Test',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            // =========================================================
            // WAHANA
            // =========================================================

            [
                'outlet_code' => 'DSW-OMSU',
                'outlet_name' => 'Wahana Omah Suwung',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-ALSANG',
                'outlet_name' => 'Wahana Alas Angon',
                'outlet_type' => 'Wahana InHouse',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-CANOE',
                'outlet_name' => 'Wahana Canoe',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-GOND',
                'outlet_name' => 'Wahana Gondola',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-KRTOSL',
                'outlet_name' => 'Wahana Kereta Osil',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-KRTWIS',
                'outlet_name' => 'Wahana Kereta Wisata',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-PADD',
                'outlet_name' => 'Wahana Paddle Board',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-PROS',
                'outlet_name' => 'Wahana Prosotan',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-KIDPAR',
                'outlet_name' => 'Wahana Kidz Paradise',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-SANPOL',
                'outlet_name' => 'Wahana Sand Pool',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-SKUTER',
                'outlet_name' => 'Wahana Skuter',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-WTRBAL',
                'outlet_name' => 'Wahana Waterball',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-TREM',
                'outlet_name' => 'Wahana Trem',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-ATV',
                'outlet_name' => 'Wahana ATV',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-PNBASG',
                'outlet_name' => 'Wahana Panahan',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-BUMBOT',
                'outlet_name' => 'Wahana Bumper Boat',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-PNHN',
                'outlet_name' => 'Wahana Panahan',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-VIRREA',
                'outlet_name' => 'Wahana Virtual Reality',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-DUJU',
                'outlet_name' => 'Wahana Dusun Salju',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-CIN7D',
                'outlet_name' => 'Wahana Cinema 7D',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-PLYGRN',
                'outlet_name' => 'Wahana Playground',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-SHWDINO',
                'outlet_name' => 'Wahana Dino Show',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-DINRID',
                'outlet_name' => 'Wahana Dino Ride',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-EMADIG',
                'outlet_name' => 'Wahana Digital',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-ARKA',
                'outlet_name' => 'Wahana Arkadia',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-GOKAR',
                'outlet_name' => 'Wahana Gokart',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-GOLF',
                'outlet_name' => 'Wahana Golf',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-TAMBOM',
                'outlet_name' => 'Wahana Tambom',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-DIALEN',
                'outlet_name' => 'Wahana Digital Playground',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-KRTTRA',
                'outlet_name' => 'Wahana Kereta Traktor',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-BBYPG',
                'outlet_name' => 'Wahana Baby Playground',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-BBCPG',
                'outlet_name' => 'Wahana Bouncy Playground',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            [
                'outlet_code' => 'DSW-CNB',
                'outlet_name' => 'Wahana Canoe',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => true,
            ],

            // =========================================================
            // AIRSOFT - NON ACTIVE
            // =========================================================

            [
                'outlet_code' => 'DSW-AIRSOF',
                'outlet_name' => 'Wahana Airsoftgun',
                'outlet_type' => 'Wahana Vendor',
                'is_active'   => false,
            ],
        ];

        foreach ($outlets as $outlet) {
            Outlet::updateOrCreate(
                [
                    'outlet_code' => $outlet['outlet_code'],
                ],
                [
                    'outlet_name' => $outlet['outlet_name'],
                    'outlet_type' => $outlet['outlet_type'],
                    'is_active'   => $outlet['is_active'],
                ]
            );
        }

        $this->command->info(
            'Outlet seeder berhasil: ' . count($outlets) . ' outlet.'
        );
    }
}