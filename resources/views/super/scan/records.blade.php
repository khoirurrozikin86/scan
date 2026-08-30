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



        <div class="card mb-4">

            <div class="card-body">

                <div class="row align-items-end">

                    <div class="col-md-4">

                        <label for="date_from" class="form-label">
                            Dari Tanggal
                        </label>

                        <input type="date" id="date_from" class="form-control" value="{{ now()->format('Y-m-d') }}">

                    </div>


                    <div class="col-md-4">

                        <label for="date_to" class="form-label">
                            Sampai Tanggal
                        </label>

                        <input type="date" id="date_to" class="form-control" value="{{ now()->format('Y-m-d') }}">

                    </div>


                    <div class="col-md-4">

                        <div class="d-flex gap-2">

                            <button type="button" id="btnFilter" class="btn btn-primary">

                                <i data-feather="search"></i>
                                Tampilkan

                            </button>


                            <button type="button" id="btnExport" class="btn btn-success">

                                <i data-feather="download"></i>
                                Export Excel

                            </button>

                        </div>

                    </div>

                </div>

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

                                <th>Outlet Type</th>

                                <th>
                                    Method
                                </th>

                                <th>
                                    Scanned At
                                </th>

                                <th>
                                    Action
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
        /*
                                                        |--------------------------------------------------------------------------
                                                        | DATATABLE
                                                        |--------------------------------------------------------------------------
                                                        */

        let table;


        $(document).ready(function() {

            /*
            |--------------------------------------------------------------------------
            | FEATHER
            |--------------------------------------------------------------------------
            */

            if (window.feather) {
                feather.replace();
            }


            /*
            |--------------------------------------------------------------------------
            | DATATABLE
            |--------------------------------------------------------------------------
            */

            table = $('#scanRecordsTable').DataTable({

                processing: true,

                serverSide: true,

                responsive: false,

                autoWidth: false,

                ajax: {

                    url: "{{ route('super.scan-records.dt') }}",

                    data: function(d) {

                        d.date_from = $('#date_from').val();

                        d.date_to = $('#date_to').val();

                    }

                },

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
                        data: 'outlet_type',
                        name: 'outlet.outlet_type'
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
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }

                ],

                order: [
                    [6, 'desc']
                ]

            });


            /*
            |--------------------------------------------------------------------------
            | FILTER
            |--------------------------------------------------------------------------
            */

            $('#btnFilter').on('click', function() {

                let dateFrom = $('#date_from').val();

                let dateTo = $('#date_to').val();


                if (!dateFrom || !dateTo) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Tanggal belum lengkap',
                        text: 'Silakan pilih tanggal dari dan sampai.'
                    });

                    return;
                }


                if (dateFrom > dateTo) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Tanggal tidak valid',
                        text: 'Dari tanggal tidak boleh lebih besar dari sampai tanggal.'
                    });

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | RELOAD DATATABLE
                |--------------------------------------------------------------------------
                */

                table.ajax.reload(null, false);

            });


            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '.btn-delete-scan',
                function() {

                    let url = $(this).attr('data-url');


                    if (!url) {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'URL delete tidak ditemukan.'
                        });

                        return;
                    }


                    Swal.fire({

                        icon: 'warning',

                        title: 'Hapus Scan?',

                        text: 'Data scan ini akan dihapus.',

                        showCancelButton: true,

                        confirmButtonText: 'Ya, Hapus',

                        cancelButtonText: 'Batal',

                        confirmButtonColor: '#d33',

                    }).then(function(result) {

                        if (!result.isConfirmed) {
                            return;
                        }


                        $.ajax({

                            url: url,

                            type: 'DELETE',

                            data: {
                                _token: "{{ csrf_token() }}"
                            },


                            success: function(response) {

                                Swal.fire({

                                    icon: 'success',

                                    title: 'Berhasil',

                                    text: response.message ||
                                        'Data scan berhasil dihapus.',

                                    showConfirmButton: false,

                                    timer: 1800

                                });


                                /*
                                |--------------------------------------------------------------------------
                                | RELOAD
                                |--------------------------------------------------------------------------
                                */

                                table.ajax.reload(
                                    null,
                                    false
                                );

                            },


                            error: function(xhr) {

                                Swal.fire({

                                    icon: 'error',

                                    title: 'Gagal',

                                    text: xhr.responseJSON?.message ||
                                        'Data scan gagal dihapus.'

                                });

                            }

                        });

                    });

                }

            );

        });



        $('#btnExport').on('click', function() {

            let dateFrom = $('#date_from').val();
            let dateTo = $('#date_to').val();

            if (!dateFrom || !dateTo) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Tanggal belum lengkap',
                    text: 'Silakan pilih tanggal terlebih dahulu.'
                });

                return;
            }

            if (dateFrom > dateTo) {

                Swal.fire({
                    icon: 'warning',
                    title: 'Tanggal tidak valid',
                    text: 'Dari tanggal tidak boleh lebih besar dari sampai tanggal.'
                });

                return;
            }

            let url = "{{ route('super.scan-records.export') }}" +
                "?date_from=" + encodeURIComponent(dateFrom) +
                "&date_to=" + encodeURIComponent(dateTo);

            window.location.href = url;

        });
    </script>
@endpush
