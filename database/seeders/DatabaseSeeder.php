<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // $this->call(UsersAndPermissionsSeeder::class);

        // $this->call([LandingSeed::class]);


        $this->call([

            UsersAndPermissionsSeeder::class,
            OutletSeeder::class,
            TicketQrcodeSeeder::class,
            UserSeeder::class,
            userOutletSeeder::class,

        ]);
    }
}
