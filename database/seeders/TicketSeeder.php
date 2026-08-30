<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            'Internet Connection Down',
            'Printer Not Responding',
            'Cannot Login Email',
            'Laptop Blue Screen',
            'VPN Access Failed',
            'Software Installation Request',
            'Network Slow Performance',
            'Windows Activation Error',
            'Scanner Not Working',
            'Outlook Sync Problem',
            'Password Reset Request',
            'Shared Folder Access Issue',
            'WiFi Connection Problem',
            'Monitor Display Issue',
            'Application Crash',
        ];

        $priorities = [
            'Low',
            'Medium',
            'High'
        ];

        $statuses = [
            'Open',
            'In Progress',
            'Resolved',
            'Closed'
        ];

        $users = User::pluck('id')->toArray();
        $categories = Category::pluck('id')->toArray();

        for ($i = 1; $i <= 150; $i++) {

            Ticket::create([

                'ticket_number' => 'TK-' . str_pad(
                    $i,
                    4,
                    '0',
                    STR_PAD_LEFT
                ),

                'title' => fake()->randomElement($titles),

                'category_id' => fake()->randomElement($categories),

                'created_by' => fake()->randomElement($users),

                'assigned_user_id' => fake()->randomElement($users),

                'priority' => fake()->randomElement($priorities),

                'status' => fake()->randomElement($statuses),

                'description' => fake()->paragraph(),

                'notes' => fake()->boolean(70)
                    ? fake()->sentence()
                    : null,

                'created_date' => fake()->dateTimeBetween(
                    '-60 days',
                    'now'
                ),

            ]);
        }
    }
}