<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Ticket;

class DashboardController extends Controller
{
    public function index()
    {
        $metrics = [

            'total_tickets' => Ticket::count(),

            'open_tickets' => Ticket::where(
                'status',
                'Open'
            )->count(),

            'in_progress_tickets' => Ticket::where(
                'status',
                'In Progress'
            )->count(),

            'high_priority_tickets' => Ticket::where(
                'priority',
                'High'
            )->count(),

            'closed_tickets' => Ticket::where(
                'status',
                'Closed'
            )->count(),
        ];

        $statusChart = [
            'Open' => Ticket::where('status', 'Open')->count(),
            'In Progress' => Ticket::where('status', 'In Progress')->count(),
            'Resolved' => Ticket::where('status', 'Resolved')->count(),
            'Closed' => Ticket::where('status', 'Closed')->count(),
        ];

        $categoryChart = Category::withCount('tickets')
            ->get()
            ->map(fn($c) => [
                'name' => $c->name,
                'total' => $c->tickets_count,
            ]);

        $recentTickets = Ticket::with([
            'category',
            'assignedUser'
        ])
            ->orderByDesc('id')
            ->take(10)
            ->get();


        return view(
            'super.dashboard',
            compact(
                'metrics',
                'statusChart',
                'categoryChart',
                'recentTickets'
            )
        );
    }
}
