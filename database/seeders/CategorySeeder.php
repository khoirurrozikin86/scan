<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $categories = [

            [
                'name' => 'Network',
                'description' => 'Network connectivity and internet issues',
            ],

            [
                'name' => 'Hardware',
                'description' => 'Computer, laptop, and hardware problems',
            ],

            [
                'name' => 'Software',
                'description' => 'Application and software related issues',
            ],

            [
                'name' => 'Email',
                'description' => 'Email account and mail service issues',
            ],

            [
                'name' => 'Printer',
                'description' => 'Printer and scanner issues',
            ],

            [
                'name' => 'Security',
                'description' => 'Security incidents and access issues',
            ],

        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
