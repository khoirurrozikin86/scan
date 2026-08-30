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



{{-- Stats Row (tambahkan atau ganti yang lama sesuai selera) --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card lift-on-hover">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary text-white"
                    style="width:46px;height:46px;">
                    <i data-feather="users" class="icon-sm"></i>
                </div>
                <div>
                    <div class="small text-muted">Total Users</div>
                    <div class="h4 mb-0"><span class="countup" data-target="{{ $metrics['users'] ?? 0 }}">0</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card lift-on-hover">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-success text-white"
                    style="width:46px;height:46px;">
                    <i data-feather="user-check" class="icon-sm"></i>
                </div>
                <div>
                    <div class="small text-muted">Pelanggan</div>
                    <div class="h4 mb-0"><span class="countup" data-target="{{ $metrics['pelanggans'] ?? 0 }}">0</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card lift-on-hover">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-warning text-dark"
                    style="width:46px;height:46px;">
                    <i data-feather="file-text" class="icon-sm"></i>
                </div>
                <div>
                    <div class="small text-muted">Tagihan Bulan Ini</div>
                    <div class="h4 mb-0">Rp {{ number_format($metrics['tagihan_amount_month'] ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card lift-on-hover">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center bg-info text-white"
                    style="width:46px;height:46px;">
                    <i data-feather="credit-card" class="icon-sm"></i>
                </div>
                <div>
                    <div class="small text-muted">Dibayar Bulan Ini</div>
                    <div class="h4 mb-0">Rp {{ number_format($metrics['paid_amount_month'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Row: Chart + Lists --}}
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-title mb-0">Tren 6 Bulan (Tagihan vs Pembayaran)</h6>
                    <div class="small text-muted">
                        Lunas: {{ $metrics['paid_count_month'] ?? 0 }} • Belum:
                        {{ $metrics['unpaid_count_month'] ?? 0 }}
                    </div>
                </div>
                <canvas id="chart-trend" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <h6 class="card-title mb-3">Outstanding (Total)</h6>
                <div class="display-6 fw-bold mb-2">
                    Rp {{ number_format($metrics['outstanding_total'] ?? 0, 0, ',', '.') }}
                </div>
                <div class="text-muted small">Total piutang belum tertagih.</div>
                <hr>
                <h6 class="card-title mb-2">Top Piutang</h6>
                <ul class="list-group list-group-flush">
                    @forelse($topAr as $ar)
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <div>
                                <div class="fw-semibold">{{ $ar->pelanggan ?? '-' }}</div>
                                <div class="small text-muted">{{ $ar->no_tagihan }}</div>
                            </div>
                            <div class="fw-semibold">Rp {{ number_format($ar->sisa, 0, ',', '.') }}</div>
                        </li>
                    @empty
                        <li class="list-group-item text-muted">Tidak ada piutang.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</div>

{{-- Row: Recent Payments --}}
<div class="row g-3">
    <div class="col-12">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="card-title mb-0">Pembayaran Terbaru</h6>
                    <a href="{{ route('super.payments.index') }}" class="small">Lihat semua</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>Paid At</th>
                                <th>No Tagihan</th>
                                <th>Pelanggan</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Ref</th>
                                <th>User</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentPayments as $p)
                                <tr>
                                    <td>{{ $p->paid_at?->format('d M Y H:i') }}</td>
                                    <td>{{ $p->tagihan?->no_tagihan }}</td>
                                    <td>{{ $p->tagihan?->pelanggan?->nama }}</td>
                                    <td class="fw-semibold">Rp {{ number_format($p->amount, 0, ',', '.') }}</td>
                                    <td>{{ strtoupper($p->method) }}</td>
                                    <td>{{ $p->ref_no }}</td>
                                    <td>{{ $p->user?->name }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-muted">Belum ada pembayaran.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
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
    (function() {
        const el = document.getElementById('chart-trend');
        if (!el || typeof Chart === 'undefined') return;

        const labels = @json(collect($trend)->pluck('label'));
        const tagihan = @json(collect($trend)->pluck('tagihan'));
        const paid = @json(collect($trend)->pluck('paid'));

        new Chart(el, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                        label: 'Tagihan',
                        data: tagihan,
                        borderWidth: 1
                    },
                    {
                        label: 'Dibayar',
                        data: paid,
                        type: 'line',
                        tension: .3,
                        borderWidth: 2,
                        pointRadius: 3
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        ticks: {
                            callback: v => 'Rp ' + Number(v).toLocaleString('id-ID')
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (c) => `${c.dataset.label}: Rp ${Number(c.raw).toLocaleString('id-ID')}`
                        }
                    },
                    legend: {
                        display: true
                    }
                }
            }
        });
    })();
</script>
@endpush
