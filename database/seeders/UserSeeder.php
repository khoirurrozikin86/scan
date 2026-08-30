<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('dusem123');

        $users = [

            // =====================================================
            // SUPER ADMIN
            // =====================================================

            [
                'name'  => 'Super Administrator',
                'email' => 'superadmin@local.com',
                'role'  => 'super_admin',
            ],

            // =====================================================
            // ADMIN
            // =====================================================

            [
                'name'  => 'Admin Wahana',
                'email' => 'admin_wahana@local.com',
                'role'  => 'admin',
            ],

            [
                'name'  => 'User Finance',
                'email' => 'user_finance@local.com',
                'role'  => 'admin',
            ],

            // =====================================================
            // USER
            // =====================================================

            [
                'name'  => 'Jhon Doe',
                'email' => 'user_jhon@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ERSA SYAWALLA',
                'email' => 'ersa@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ULFANA',
                'email' => 'ulfana@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'SIRIKIT LIOKTA JATI',
                'email' => 'sirikit@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'DWI PRAYOGO',
                'email' => 'dwi_prayogo@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'WIDIYAWATI',
                'email' => 'widiyawati@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ARYAN DWI FERDIANSYAH',
                'email' => 'aryan@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'DESTA EKO',
                'email' => 'desta@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'YOSI ROSIANA',
                'email' => 'yosi@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'YENI RAHAYU',
                'email' => 'yeni@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'RANGGA HANIF FEBRIAL',
                'email' => 'rangga@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'DANANG RAMADAN',
                'email' => 'danang@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'AHMAD SOLICHIN',
                'email' => 'ahmad@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'IIS MAWATI',
                'email' => 'iis_mawati@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'SHELLA RISTA',
                'email' => 'shella@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'VINDA PRADHANNY',
                'email' => 'vinda@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'VINA LUTFIANA',
                'email' => 'vina@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'NADIA PUTRI WULANDARI',
                'email' => 'nadia@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'NADILA',
                'email' => 'nadila@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'RIZKI DWI',
                'email' => 'rizki@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'DEVIT',
                'email' => 'devit@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'RIO',
                'email' => 'rio@local.com',
                'role' => 'user',
            ],

            [
                'name'  => 'EKO SUDAR',
                'email' => 'eko_sudar@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'RUPADI',
                'email' => 'rupadi@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'INDRA',
                'email' => 'indra@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'FERI',
                'email' => 'feri@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'WARYONO',
                'email' => 'waryono@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ARIFIN',
                'email' => 'arifin@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'FATKUROHMAN',
                'email' => 'fatkurohman@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'SLAMET',
                'email' => 'slamet@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ESB Eropa',
                'email' => 'esb_eropa@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ESB Prosotan',
                'email' => 'esb_prosotan@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ESB Banyubiru',
                'email' => 'esb_banyubiru@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ESB Omah Suwung',
                'email' => 'esb_omsu@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ESB Cinema 7D',
                'email' => 'esb_cinema7d@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ESB Playground',
                'email' => 'esb_playground@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ESB Emaji Digital',
                'email' => 'esb_emaji@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ESB Kidz Paradise',
                'email' => 'esb_kidzparadise@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ESB Arkadia',
                'email' => 'esb_arkadia@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'ASIH',
                'email' => 'asih@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'HAIKAL',
                'email' => 'haikal@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'TIKA',
                'email' => 'tika@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'MEISYA',
                'email' => 'meisya@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'DIAN',
                'email' => 'dian@local.com',
                'role'  => 'user',
            ],

            [
                'name'  => 'Defa',
                'email' => 'defa@local.com',
                'role'  => 'user',
            ],
        ];

        foreach ($users as $data) {

            $user = User::updateOrCreate(
                [
                    'email' => $data['email'],
                ],
                [
                    'name'     => $data['name'],
                    'password' => $password,
                ]
            );

            $user->syncRoles([
                $data['role'],
            ]);
        }
    }
}
