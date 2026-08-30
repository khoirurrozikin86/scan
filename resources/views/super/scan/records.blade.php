@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h4 class="mb-1">
                    Scan Records
                </h4>

                <p class="text-muted mb-0">
                    Riwayat penggunaan tiket.
                </p>

            </div>

        </div>


        <div class="card">

            <div class="card-body">

                <div class="table-responsive">

                    <table id="scanRecordsTable" class="table table-bordered table-striped align-middle w-100">

                        <thead>

                            <tr>

                                <th>
                                    No Tiket
                                </th>

                                <th>
                                    QR Code
                                </th>

                                <th>
                                    Ticket Type
                                </th>

                                <th>
                                    Operator
                                </th>

                                <th>
                                    Outlet
                                </th>

                                <th>
                                    Method
                                </th>

                                <th>
                                    Scanned At
                                </th>

                                <th>
                                    Remark
                                </th>

                            </tr>

                        </thead>

                        <tbody>
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {

                if (window.feather) {
                    feather.replace();
                }


                $('#scanRecordsTable').DataTable({

                    processing: true,

                    serverSide: true,

                    responsive: false,

                    autoWidth: false,

                    ajax: '{{ route('super.scan-records.dt') }}',

                    columns: [

                        {
                            data: 'no_tiket',
                            name: 'no_tiket'
                        },

                        {
                            data: 'qrcode',
                            name: 'qrcode'
                        },

                        {
                            data: 'ticket_type',
                            name: 'ticket_type'
                        },

                        {
                            data: 'user_name',
                            name: 'user.name'
                        },

                        {
                            data: 'outlet_name',
                            name: 'outlet.outlet_name'
                        },

                        {
                            data: 'scan_method',
                            name: 'scan_method'
                        },

                        {
                            data: 'scanned_at',
                            name: 'scanned_at'
                        },

                        {
                            data: 'remark',
                            name: 'remark'
                        }

                    ],

                    order: [
                        [6, 'desc']
                    ]

                });

            }

        );
    </script>
@endpush
