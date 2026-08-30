{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}


@extends('layouts.admin')

@section('title', 'dashboard')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Dashoard</a></li>
            <li class="breadcrumb-item active" aria-current="page">index</li>
        </ol>
    </nav>
@endsection

@section('content')


    {{-- Stats Row (tambahkan atau ganti yang lama sesuai selera) --}}
    <div class="row g-3 mb-4">

        <div class="col">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Total Tickets</div>
                    <h2 class="fw-bold mb-0">
                        {{ $metrics['total_tickets'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Open Tickets</div>
                    <h2 class="fw-bold text-primary mb-0">
                        {{ $metrics['open_tickets'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">In Progress</div>
                    <h2 class="fw-bold text-warning mb-0">
                        {{ $metrics['in_progress_tickets'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">High Priority</div>
                    <h2 class="fw-bold text-danger mb-0">
                        {{ $metrics['high_priority_tickets'] }}
                    </h2>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="text-muted small">Closed Tickets</div>
                    <h2 class="fw-bold text-success mb-0">
                        {{ $metrics['closed_tickets'] ?? 0 }}
                    </h2>
                </div>
            </div>
        </div>

    </div>

    {{-- Row: Chart + Lists --}}

    <div class="row g-3 mb-4">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">

                    <h6 class="mb-3">
                        Tickets by Status
                    </h6>

                    <canvas id="statusChart"></canvas>

                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">

                    <h6 class="mb-3">
                        Tickets by Category
                    </h6>

                    <canvas id="categoryChart"></canvas>

                </div>
            </div>
        </div>
    </div>


    {{-- Row: Recent Payments --}}

    <div class="card mt-4">
        <div class="card-body">

            <h6 class="mb-3">
                Recent Tickets
            </h6>

            <div class="table-responsive">

                <table class="table">

                    <thead>
                        <tr>
                            <th>No Ticket</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Priority</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($recentTickets as $ticket)
                            <tr>

                                <td>
                                    {{ $ticket->ticket_number }}
                                </td>

                                <td>
                                    {{ $ticket->title }}
                                </td>

                                <td>
                                    {{ $ticket->category?->name }}
                                </td>

                                <td>
                                    {{ $ticket->status }}
                                </td>

                                <td>
                                    {{ $ticket->priority }}
                                </td>

                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>
    </div>


@endsection




{{-- Modal View Payments --}}

@push('vendor-styles')
    <link rel="stylesheet"
        href="{{ asset('vendor/nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.css') }}">
@endpush
@push('vendor-scripts')
    <script src="{{ asset('vendor/nobleui/assets/vendors/datatables.net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('vendor/nobleui/assets/vendors/datatables.net-bs5/dataTables.bootstrap5.js') }}"></script>
@endpush




@push('scripts')
    {{-- CDN Chart.js (hanya untuk dashboard) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>


    <script>
        const statusChart = @json(array_values($statusChart));

        new Chart(
            document.getElementById('statusChart'), {
                type: 'doughnut',
                data: {
                    labels: [
                        'Open',
                        'In Progress',
                        'Resolved',
                        'Closed'
                    ],
                    datasets: [{
                        data: statusChart
                    }]
                }
            }
        );

        const categoryData = @json($categoryChart);

        new Chart(
            document.getElementById('categoryChart'), {
                type: 'bar',
                data: {
                    labels: categoryData.map(x => x.name),
                    datasets: [{
                        label: 'Tickets',
                        data: categoryData.map(x => x.total)
                    }]
                }
            }
        );
    </script>
@endpush
