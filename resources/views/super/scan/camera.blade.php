@extends('layouts.admin')


@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    Camera Scan
                </h4>

                <p class="text-muted mb-0">
                    Scan QR Code tiket menggunakan kamera.
                </p>
            </div>

        </div>


        {{-- OUTLET --}}
        <div class="card mb-4">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">

                        <label for="outlet_id" class="form-label fw-semibold">

                            Outlet

                        </label>

                        <select id="outlet_id" class="form-select">

                            <option value="">
                                -- Pilih Outlet --
                            </option>

                            @foreach ($outlets as $outlet)
                                <option value="{{ $outlet->id }}">

                                    {{ $outlet->outlet_code }}
                                    -
                                    {{ $outlet->outlet_name }}

                                </option>
                            @endforeach

                        </select>

                        {{-- <div id="outletError" class="text-danger small mt-2">
                        </div> --}}

                    </div>

                </div>

            </div>

        </div>


        {{-- CAMERA --}}
        <div id="cameraCard" class="card">

            <div class="card-body">

                <div class="text-center">

                    <div id="cameraWrapper" class="mx-auto"
                        style="
                        max-width:520px;
                        position:relative;
                    ">

                        <video id="cameraPreview" autoplay playsinline muted
                            style="
                            width:100%;
                            min-height:300px;
                            object-fit:cover;
                            background:#111;
                            border-radius:12px;
                        ">
                        </video>


                        {{-- SCAN FRAME --}}

                        <div
                            style="
                            position:absolute;
                            top:50%;
                            left:50%;
                            transform:translate(-50%, -50%);
                            width:70%;
                            height:35%;
                            border:3px solid #fff;
                            border-radius:12px;
                            pointer-events:none;
                        ">
                        </div>

                    </div>


                    {{-- STATUS --}}

                    <div id="cameraStatus" class="mt-3 text-muted">

                        Pilih outlet terlebih dahulu.

                    </div>


                    {{-- BUTTON --}}

                    <div class="mt-3">

                        <button type="button" id="btnStartCamera" class="btn btn-primary">

                            <i data-feather="camera"></i>

                            Mulai Camera

                        </button>


                        <button type="button" id="btnStopCamera" class="btn btn-danger d-none">

                            <i data-feather="square"></i>

                            Stop Camera

                        </button>

                    </div>

                </div>

            </div>

        </div>


        {{-- RESULT --}}

        <div class="card mt-4">

            <div class="card-header">
                <h5 class="mb-0">
                    <i data-feather="clock"></i>
                    10 Scan Terakhir
                </h5>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>
                            <tr>
                                <th width="50">No</th>
                                <th>QR Code</th>
                                <th>No Tiket</th>
                                <th>Ticket Type</th>
                                <th>Method</th>
                                <th>Waktu Scan</th>
                            </tr>
                        </thead>

                        <tbody id="scanHistory">

                            <tr>
                                <td colspan="6" class="text-center text-muted">
                                    Belum ada data scan.
                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection





<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

@push('scripts')
    <script>
        $(document).ready(function() {

            loadHistory();

            let qrScanner = null;
            let scanning = false;
            let processing = false;


            /*
            |--------------------------------------------------------------------------
            | START CAMERA
            |--------------------------------------------------------------------------
            */

            $('#btnStartCamera').on('click', function() {

                let outletId = $('#outlet_id').val();

                if (!outletId) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Pilih Outlet',
                        text: 'Silakan pilih outlet terlebih dahulu.',
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true
                    });

                    return;
                }

                startCamera();

            });


            /*
            |--------------------------------------------------------------------------
            | START QR SCANNER
            |--------------------------------------------------------------------------
            */

            function startCamera() {

                if (scanning) {
                    return;
                }

                qrScanner = new Html5Qrcode('cameraWrapper');

                scanning = true;

                $('#btnStartCamera').addClass('d-none');
                $('#btnStopCamera').removeClass('d-none');

                $('#cameraStatus')
                    .removeClass('text-muted text-danger')
                    .addClass('text-success')
                    .text('Camera aktif. Arahkan QR Code ke kamera.');


                qrScanner.start(

                    {
                        facingMode: 'environment'
                    },

                    {
                        fps: 10,

                        qrbox: {
                            width: 300,
                            height: 150
                        }

                    },

                    function(decodedText) {

                        /*
                        |--------------------------------------------------------------------------
                        | QR TERBACA
                        |--------------------------------------------------------------------------
                        */

                        if (processing) {
                            return;
                        }

                        processing = true;

                        console.log(
                            'QR:',
                            decodedText
                        );


                        /*
                        |--------------------------------------------------------------------------
                        | PAUSE SCANNER
                        |--------------------------------------------------------------------------
                        */

                        qrScanner.pause(true);


                        processScan(decodedText);

                    },

                    function(errorMessage) {

                        // Tidak perlu melakukan apa-apa
                        // selama QR belum terbaca

                    }

                ).catch(function(error) {

                    console.error(error);

                    scanning = false;

                    $('#btnStartCamera').removeClass('d-none');
                    $('#btnStopCamera').addClass('d-none');

                    Swal.fire({
                        icon: 'error',
                        title: 'Camera Tidak Bisa Dibuka',
                        text: 'Pastikan izin kamera sudah diberikan.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });

                });

            }


            /*
            |--------------------------------------------------------------------------
            | PROCESS SCAN
            |--------------------------------------------------------------------------
            */

            function processScan(qrcode) {

                let outletId = $('#outlet_id').val();


                $.ajax({

                    url: "{{ route('super.scan-records.scan') }}",

                    type: 'POST',

                    data: {

                        _token: "{{ csrf_token() }}",

                        outlet_id: outletId,

                        qrcode: qrcode,

                        scan_method: 'camera'

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | SUCCESS
                    |--------------------------------------------------------------------------
                    */

                    success: function(response) {

                        Swal.fire({

                            icon: 'success',

                            title: 'Tiket Valid',

                            text: response.message ||
                                'Tiket berhasil diterima.',

                            showConfirmButton: false,

                            timer: 3000,

                            timerProgressBar: true

                        });


                        $('#cameraStatus')
                            .removeClass(
                                'text-danger text-muted'
                            )
                            .addClass(
                                'text-success'
                            )
                            .text(
                                '✓ Tiket berhasil diterima.'
                            );


                        loadHistory();


                        /*
                        |--------------------------------------------------------------------------
                        | LANJUT SCAN
                        |--------------------------------------------------------------------------
                        */

                        setTimeout(function() {

                            processing = false;

                            if (
                                qrScanner &&
                                scanning
                            ) {

                                qrScanner.resume();

                            }

                        }, 1000);


                    },


                    /*
                    |--------------------------------------------------------------------------
                    | ERROR
                    |--------------------------------------------------------------------------
                    */

                    error: function(xhr) {

                        let message =
                            'Tiket tidak dapat diproses.';

                        let title =
                            'Scan Ditolak';


                        if (
                            xhr.responseJSON &&
                            xhr.responseJSON.message
                        ) {

                            message =
                                xhr.responseJSON.message;

                        }


                        if (xhr.status === 404) {

                            title =
                                'Tiket Tidak Ditemukan';

                        }


                        if (xhr.status === 422) {

                            title =
                                'Tiket Sudah Digunakan';

                        }


                        if (xhr.status === 403) {

                            title =
                                'Akses Ditolak';

                        }


                        Swal.fire({

                            icon: 'error',

                            title: title,

                            text: message,

                            showConfirmButton: false,

                            timer: 3000,

                            timerProgressBar: true

                        });


                        $('#cameraStatus')
                            .removeClass(
                                'text-success text-muted'
                            )
                            .addClass(
                                'text-danger'
                            )
                            .text(
                                '✕ ' + message
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | SCAN LAGI
                        |--------------------------------------------------------------------------
                        */

                        setTimeout(function() {

                            processing = false;

                            if (
                                qrScanner &&
                                scanning
                            ) {

                                qrScanner.resume();

                            }

                        }, 1000);

                    }

                });

            }


            /*
            |--------------------------------------------------------------------------
            | STOP CAMERA
            |--------------------------------------------------------------------------
            */

            $('#btnStopCamera').on('click', function() {

                stopCamera();

            });


            function stopCamera() {

                if (!qrScanner) {
                    return;
                }

                qrScanner.stop()
                    .then(function() {

                        qrScanner.clear();

                        qrScanner = null;

                        scanning = false;

                        processing = false;

                        $('#btnStartCamera')
                            .removeClass('d-none');

                        $('#btnStopCamera')
                            .addClass('d-none');

                        $('#cameraStatus')
                            .removeClass(
                                'text-success text-danger'
                            )
                            .addClass(
                                'text-muted'
                            )
                            .text(
                                'Camera berhenti.'
                            );

                    })
                    .catch(function(error) {

                        console.error(error);

                    });

            }


            /*
            |--------------------------------------------------------------------------
            | OUTLET CHANGE
            |--------------------------------------------------------------------------
            */

            $('#outlet_id').on('change', function() {

                if (!$(this).val()) {

                    stopCamera();

                    $('#cameraStatus')
                        .text(
                            'Pilih outlet terlebih dahulu.'
                        );




                    loadHistory();


                    return;
                }

                $('#cameraStatus')
                    .text(
                        'Outlet dipilih. Silakan mulai camera.'
                    );


                loadHistory();

            });


            /*
            |--------------------------------------------------------------------------
            | CLEANUP
            |--------------------------------------------------------------------------
            */

            $(window).on('beforeunload', function() {

                stopCamera();

            });





        });







        function loadHistory() {

            let outletId = $('#outlet_id').val();

            let history = $('#scanHistory');

            let historyOutlet = $('#historyOutlet');


            /*
            |--------------------------------------------------------------------------
            | BELUM PILIH OUTLET
            |--------------------------------------------------------------------------
            */

            if (!outletId) {

                history.html(`
            <tr>
                <td colspan="6"
                    class="text-center text-muted py-4">

                    Pilih outlet terlebih dahulu.

                </td>
            </tr>
        `);

                historyOutlet.text('-');

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | NAMA OUTLET
            |--------------------------------------------------------------------------
            */

            let outletText = $('#outlet_id option:selected')
                .text()
                .trim();

            historyOutlet.text(outletText);


            /*
            |--------------------------------------------------------------------------
            | LOADING
            |--------------------------------------------------------------------------
            */

            history.html(`
        <tr>
            <td colspan="6"
                class="text-center text-muted py-4">

                Memuat history...

            </td>
        </tr>
    `);


            /*
            |--------------------------------------------------------------------------
            | AJAX
            |--------------------------------------------------------------------------
            */

            $.ajax({

                url: "{{ route('super.scan-records.history') }}",

                type: "GET",

                data: {
                    outlet_id: outletId
                },

                success: function(response) {

                    history.empty();


                    /*
                    |--------------------------------------------------------------------------
                    | TIDAK ADA DATA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !response.data ||
                        response.data.length === 0
                    ) {

                        history.html(`
                    <tr>
                        <td colspan="6"
                            class="text-center text-muted py-4">

                            Belum ada scan.

                        </td>
                    </tr>
                `);

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | DATA
                    |--------------------------------------------------------------------------
                    */

                    $.each(response.data, function(index, item) {

                        let methodBadge;


                        if (item.scan_method === 'scanner') {

                            methodBadge = `
                        <span class="badge bg-primary">
                            Scanner
                        </span>
                    `;

                        } else {

                            methodBadge = `
                        <span class="badge bg-success">
                            Camera
                        </span>
                    `;

                        }


                        history.append(`

                    <tr>

                        <td>
                            ${index + 1}
                        </td>

                        <td>
                            <strong>
                                ${item.qrcode ?? '-'}
                            </strong>
                        </td>

                        <td>
                            ${item.no_tiket ?? '-'}
                        </td>

                        <td>
                            ${item.ticket_type ?? '-'}
                        </td>

                        <td>
                            ${methodBadge}
                        </td>

                        <td>
                            ${item.scanned_at ?? '-'}
                        </td>

                    </tr>

                `);

                    });

                },


                /*
                |--------------------------------------------------------------------------
                | ERROR
                |--------------------------------------------------------------------------
                */

                error: function(xhr) {

                    console.error(
                        'History error:',
                        xhr.responseJSON
                    );

                    history.html(`
                <tr>
                    <td colspan="6"
                        class="text-center text-danger py-4">

                        Gagal mengambil history scan.

                    </td>
                </tr>
            `);

                }

            });

        }
    </script>
@endpush
