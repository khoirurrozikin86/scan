@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        {{-- =========================================================
     * HEADER
     * ========================================================= --}}
        <div class="row">

            <div class="col-12">

                <div class="page-header">

                    <h4 class="page-title">
                        Barcode Scanner
                    </h4>

                    <p class="text-muted">
                        Scan tiket menggunakan barcode scanner.
                    </p>

                </div>

            </div>

        </div>


        {{-- =========================================================
     * SCANNER
     * ========================================================= --}}
        <div class="row">

            <div class="col-lg-8 col-xl-6 mx-auto">

                <div class="card">

                    <div class="card-body">


                        {{-- =================================================
                     * OUTLET
                     * ================================================= --}}
                        <div class="mb-4">

                            <label for="outlet_id" class="form-label">
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

                        </div>


                        {{-- =================================================
                     * BARCODE INPUT
                     * ================================================= --}}
                        <div class="mb-4">

                            <label for="barcodeInput" class="form-label">
                                Scan Tiket
                            </label>

                            <input type="text" id="barcodeInput" class="form-control form-control-lg text-center"
                                placeholder="Scan barcode tiket..." autocomplete="off" disabled>

                            <div class="form-text text-center mt-2">

                                Arahkan barcode scanner ke tiket,
                                kemudian scan.

                            </div>

                        </div>


                        {{-- =================================================
                     * STATUS
                     * ================================================= --}}
                        {{-- <div id="scanStatus" class="alert alert-secondary text-center mb-0">

                            Silakan pilih outlet terlebih dahulu.

                        </div> --}}


                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
     * LAST SCAN RESULT
     * ========================================================= --}}
        <div class="card mt-4">

            <div class="card-header d-flex justify-content-between align-items-center">

                <div>
                    <h5 class="mb-0">
                        History Scan
                    </h5>

                    <small class="text-muted">
                        10 scan terbaru
                    </small>
                </div>

                <span id="historyOutlet" class="badge bg-secondary">
                    -
                </span>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover align-middle mb-0">

                        <thead>

                            <tr>
                                <th width="60">No</th>
                                <th>QR Code</th>
                                <th>No Tiket</th>
                                <th>Ticket Type</th>
                                <th>Method</th>
                                <th>Waktu Scan</th>
                            </tr>

                        </thead>

                        <tbody id="scanHistory">

                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">

                                    Pilih outlet terlebih dahulu.

                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {

            /*
            |--------------------------------------------------------------------------
            | ELEMENT
            |--------------------------------------------------------------------------
            */

            const outlet = $('#outlet_id');
            const barcode = $('#barcodeInput');
            const status = $('#scanStatus');
            const history = $('#scanHistory');
            const historyOutlet = $('#historyOutlet');


            /*
            |--------------------------------------------------------------------------
            | URL
            |--------------------------------------------------------------------------
            */

            const scanUrl = "{{ route('super.scan-records.scan') }}";

            const historyUrl = "{{ route('super.scan-records.history') }}";


            /*
            |--------------------------------------------------------------------------
            | FOCUS BARCODE
            |--------------------------------------------------------------------------
            */

            function focusBarcode() {
                if (outlet.val()) {

                    barcode
                        .prop('disabled', false)
                        .focus();

                }
            }


            /*
            |--------------------------------------------------------------------------
            | LOAD HISTORY
            |--------------------------------------------------------------------------
            */

            function loadHistory() {
                let outletId = outlet.val();

                if (!outletId) {

                    history.html(`
                <tr>
                    <td
                        colspan="4"
                        class="text-center text-muted py-4"
                    >
                        Pilih outlet untuk melihat history.
                    </td>
                </tr>
            `);

                    historyOutlet.text('-');

                    return;
                }


                $.ajax({

                    url: historyUrl,

                    type: 'GET',

                    data: {
                        outlet_id: outletId
                    },

                    success: function(response) {

                        history.empty();


                        if (!response.data || response.data.length === 0) {

                            history.html(`
                        <tr>
                            <td
                                colspan="4"
                                class="text-center text-muted py-4"
                            >
                                Belum ada history scan.
                            </td>
                        </tr>
                    `);

                            return;
                        }


                        $.each(response.data, function(index, item) {

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
                                ${item.scan_method === 'scanner'
                                    ? '<span class="badge bg-primary">Scanner</span>'
                                    : '<span class="badge bg-success">Camera</span>'
                                }
                            </td>

                            <td>
                                ${item.scanned_at ?? '-'}
                            </td>

                        </tr>

                    `);

                        });

                    },

                    error: function() {

                        history.html(`
                    <tr>
                        <td
                            colspan="4"
                            class="text-center text-danger py-4"
                        >
                            Gagal mengambil history scan.
                        </td>
                    </tr>
                `);

                    }

                });

            }


            /*
            |--------------------------------------------------------------------------
            | OUTLET CHANGE
            |--------------------------------------------------------------------------
            */

            outlet.on('change', function() {

                let outletId = $(this).val();


                if (!outletId) {

                    barcode
                        .val('')
                        .prop('disabled', true);

                    status
                        .removeClass(
                            'alert-success alert-danger alert-warning'
                        )
                        .addClass(
                            'alert-secondary'
                        )
                        .text(
                            'Silakan pilih outlet terlebih dahulu.'
                        );

                    loadHistory();

                    return;
                }


                let outletText = $(this)
                    .find('option:selected')
                    .text()
                    .trim();


                historyOutlet.text(outletText);


                barcode
                    .val('')
                    .prop('disabled', false)
                    .focus();


                status
                    .removeClass(
                        'alert-secondary alert-danger alert-warning'
                    )
                    .addClass(
                        'alert-success'
                    )
                    .text(
                        'Outlet siap. Silakan scan tiket.'
                    );


                /*
                |--------------------------------------------------------------------------
                | LOAD HISTORY OUTLET
                |--------------------------------------------------------------------------
                */

                loadHistory();

            });


            /*
            |--------------------------------------------------------------------------
            | BARCODE SCAN
            |--------------------------------------------------------------------------
            */

            barcode.on('keydown', function(e) {

                if (e.key !== 'Enter') {
                    return;
                }

                e.preventDefault();


                let qrcode = $.trim(
                    barcode.val()
                );

                let outletId = outlet.val();


                /*
                |--------------------------------------------------------------------------
                | VALIDASI OUTLET
                |--------------------------------------------------------------------------
                */

                if (!outletId) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Outlet Belum Dipilih',
                        text: 'Silakan pilih outlet terlebih dahulu.',
                        confirmButtonText: 'OK'
                    });

                    barcode
                        .val('')
                        .focus();

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | VALIDASI QR
                |--------------------------------------------------------------------------
                */

                if (!qrcode) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | LOCK
                |--------------------------------------------------------------------------
                */

                barcode.prop(
                    'disabled',
                    true
                );


                /*
                |--------------------------------------------------------------------------
                | AJAX SCAN
                |--------------------------------------------------------------------------
                */

                $.ajax({

                    url: scanUrl,

                    type: 'POST',

                    data: {

                        _token: "{{ csrf_token() }}",

                        outlet_id: outletId,

                        qrcode: qrcode,
                        scan_method: 'scanner'

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

                            timer: 1800,

                            timerProgressBar: true

                        });


                        /*
                        |--------------------------------------------------------------------------
                        | STATUS
                        |--------------------------------------------------------------------------
                        */

                        status
                            .removeClass(
                                'alert-warning alert-danger alert-secondary'
                            )
                            .addClass(
                                'alert-success'
                            )
                            .text(
                                '✓ Tiket berhasil diterima.'
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | CLEAR
                        |--------------------------------------------------------------------------
                        */

                        barcode
                            .val('')
                            .prop('disabled', false)
                            .focus();


                        /*
                        |--------------------------------------------------------------------------
                        | REFRESH HISTORY
                        |--------------------------------------------------------------------------
                        */

                        loadHistory();

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

                        let icon =
                            'error';


                        if (
                            xhr.responseJSON &&
                            xhr.responseJSON.message
                        ) {

                            message =
                                xhr.responseJSON.message;

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | TIKET TIDAK DITEMUKAN
                        |--------------------------------------------------------------------------
                        */

                        if (xhr.status === 404) {

                            title =
                                'Tiket Tidak Ditemukan';

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | SUDAH SCAN
                        |--------------------------------------------------------------------------
                        */

                        if (xhr.status === 422) {

                            title =
                                'Tiket Sudah Digunakan';

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | AKSES OUTLET
                        |--------------------------------------------------------------------------
                        */

                        if (xhr.status === 403) {

                            title =
                                'Akses Ditolak';

                        }


                        /*
                        |--------------------------------------------------------------------------
                        | SWEET ALERT
                        |--------------------------------------------------------------------------
                        */

                        Swal.fire({

                            icon: icon,

                            title: title,

                            text: message,


                            timer: 1800,
                            timerProgressBar: true,

                            confirmButtonText: 'OK'

                        });


                        /*
                        |--------------------------------------------------------------------------
                        | STATUS
                        |--------------------------------------------------------------------------
                        */

                        status
                            .removeClass(
                                'alert-warning alert-success alert-secondary'
                            )
                            .addClass(
                                'alert-danger'
                            )
                            .text(
                                '✕ ' + message
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | READY SCAN LAGI
                        |--------------------------------------------------------------------------
                        */

                        barcode
                            .val('')
                            .prop('disabled', false)
                            .focus();

                    }

                });

            });


            /*
            |--------------------------------------------------------------------------
            | CLICK PAGE → FOCUS SCANNER
            |--------------------------------------------------------------------------
            */

            $(document).on('click', function(e) {

                if (
                    $(e.target).closest('#outlet_id').length
                ) {
                    return;
                }

                focusBarcode();

            });


            /*
            |--------------------------------------------------------------------------
            | INITIAL
            |--------------------------------------------------------------------------
            */

            barcode.prop(
                'disabled',
                !outlet.val()
            );


            if (outlet.val()) {

                loadHistory();

                focusBarcode();

            }

        });


        function loadHistory() {
            let outletId = $('#outlet_id').val();

            if (!outletId) {

                $('#scanHistory').html(`
            <tr>
                <td
                    colspan="6"
                    class="text-center text-muted py-4">

                    Pilih outlet terlebih dahulu.

                </td>
            </tr>
        `);

                $('#historyOutlet').text('-');

                return;
            }


            $.ajax({

                url: "{{ route('super.scan-records.history') }}",

                type: "GET",

                data: {
                    outlet_id: outletId
                },

                success: function(response) {

                    let tbody = $('#scanHistory');

                    tbody.empty();


                    /*
                    |--------------------------------------------------------------------------
                    | TIDAK ADA DATA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !response.data ||
                        response.data.length === 0
                    ) {

                        tbody.html(`
                    <tr>
                        <td
                            colspan="6"
                            class="text-center text-muted py-4">

                            Belum ada scan.

                        </td>
                    </tr>
                `);

                        return;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | DATA HISTORY
                    |--------------------------------------------------------------------------
                    */

                    $.each(response.data, function(index, item) {

                        let methodBadge = '';

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


                        tbody.append(`

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

                error: function(xhr) {

                    $('#scanHistory').html(`
                <tr>
                    <td
                        colspan="6"
                        class="text-center text-danger py-4">

                        Gagal mengambil history scan.

                    </td>
                </tr>
            `);

                    console.error(
                        xhr.responseJSON
                    );

                }

            });
        }






        let scanBuffer = '';
        let scanTimer = null;
        let lastKeyTime = 0;

        $(document).on('keydown', function(e) {

            const now = Date.now();

            // Abaikan tombol kontrol
            if (
                e.key === 'Shift' ||
                e.key === 'Control' ||
                e.key === 'Alt' ||
                e.key === 'Meta'
            ) {
                return;
            }

            // ENTER = scanner selesai mengirim barcode
            if (e.key === 'Enter') {

                e.preventDefault();

                if (!scanBuffer) {
                    return;
                }

                let qrcode = scanBuffer;

                scanBuffer = '';

                clearTimeout(scanTimer);

                $('#barcodeInput').val('');

                // proses scan
                processScan(qrcode);

                return;
            }

            // hanya karakter
            if (e.key.length !== 1) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | DETEKSI KECEPATAN SCANNER
            |--------------------------------------------------------------------------
            */

            if (
                lastKeyTime > 0 &&
                (now - lastKeyTime) > 100
            ) {

                // terlalu lambat → dianggap ketikan manual
                scanBuffer = '';

            }

            lastKeyTime = now;

            scanBuffer += e.key;

            $('#barcodeInput').val(scanBuffer);

            /*
            |--------------------------------------------------------------------------
            | RESET
            |--------------------------------------------------------------------------
            */

            clearTimeout(scanTimer);

            scanTimer = setTimeout(function() {

                scanBuffer = '';
                $('#barcodeInput').val('');

            }, 300);

        });
    </script>
@endpush
