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
                                placeholder="Scan barcode tiket..." autocomplete="off" readonly disabled>

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
            const history = $('#scanHistory');
            const historyOutlet = $('#historyOutlet');

            const scanUrl = "{{ route('super.scan-records.scan') }}";
            const historyUrl = "{{ route('super.scan-records.history') }}";


            /*
            |--------------------------------------------------------------------------
            | SCANNER CONFIG
            |--------------------------------------------------------------------------
            |
            | Barcode scanner biasanya mengirim karakter sangat cepat lalu ENTER.
            | Ketikan keyboard biasa akan dibuang jika jeda antar karakter terlalu lama.
            |
            */

            const SCANNER_MAX_INTERVAL = 120; // ms antar karakter
            const SCANNER_TIMEOUT = 500; // reset buffer jika terlalu lama
            const MIN_BARCODE_LENGTH = 3;

            let scanBuffer = '';
            let scanTimer = null;
            let lastKeyTime = 0;
            let processingScan = false;


            /*
            |--------------------------------------------------------------------------
            | FOCUS
            |--------------------------------------------------------------------------
            */

            function focusBarcode() {

                if (!outlet.val() || processingScan) {
                    return;
                }

                barcode
                    .prop('disabled', false)
                    .prop('readonly', true)
                    .focus();
            }


            /*
            |--------------------------------------------------------------------------
            | CLEAN BARCODE
            |--------------------------------------------------------------------------
            */

            function cleanBarcode(value) {

                return String(value || '')
                    .replace(/[\r\n\t]/g, '')
                    .trim();
            }


            /*
            |--------------------------------------------------------------------------
            | LOAD HISTORY
            |--------------------------------------------------------------------------
            */

            function loadHistory() {

                const outletId = outlet.val();

                if (!outletId) {

                    history.html(`
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        Pilih outlet terlebih dahulu.
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
                            <td colspan="6" class="text-center text-muted py-4">
                                Belum ada scan.
                            </td>
                        </tr>
                    `);

                            return;
                        }

                        $.each(response.data, function(index, item) {

                            const methodBadge =
                                item.scan_method === 'scanner' ?
                                '<span class="badge bg-primary">Scanner</span>' :
                                '<span class="badge bg-success">Camera</span>';

                            history.append(`
                        <tr>
                            <td>${index + 1}</td>

                            <td>
                                <strong>${item.qrcode ?? '-'}</strong>
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

                        history.html(`
                    <tr>
                        <td colspan="6" class="text-center text-danger py-4">
                            Gagal mengambil history scan.
                        </td>
                    </tr>
                `);

                        console.error('History error:', xhr.responseJSON);
                    }
                });
            }


            /*
            |--------------------------------------------------------------------------
            | PROCESS SCAN
            |--------------------------------------------------------------------------
            */

            function processScan(rawValue) {

                let qrcode = cleanBarcode(rawValue);
                const outletId = outlet.val();

                if (!outletId) {

                    Swal.fire({
                        icon: 'warning',
                        title: 'Outlet Belum Dipilih',
                        text: 'Silakan pilih outlet terlebih dahulu.',
                        confirmButtonText: 'OK'
                    });

                    resetScanner();
                    return;
                }

                if (!qrcode || qrcode.length < MIN_BARCODE_LENGTH) {
                    resetScanner();
                    return;
                }

                if (processingScan) {
                    return;
                }

                processingScan = true;

                barcode.prop('disabled', true);

                $.ajax({

                    url: scanUrl,

                    type: 'POST',

                    data: {
                        _token: "{{ csrf_token() }}",
                        outlet_id: outletId,
                        qrcode: qrcode,
                        scan_method: 'scanner'
                    },

                    success: function(response) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Tiket Valid',
                            text: response.message || 'Tiket berhasil diterima.',
                            showConfirmButton: false,
                            timer: 1800,
                            timerProgressBar: true
                        });

                        /*
                        |--------------------------------------------------------------------------
                        | HISTORY LANGSUNG REFRESH
                        |--------------------------------------------------------------------------
                        */

                        loadHistory();
                    },

                    error: function(xhr) {

                        let message = 'Tiket tidak dapat diproses.';
                        let title = 'Scan Ditolak';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        if (xhr.status === 404) {
                            title = 'Tiket Tidak Ditemukan';
                        }

                        if (xhr.status === 422) {
                            title = 'Tiket Sudah Digunakan';
                        }

                        if (xhr.status === 403) {
                            title = 'Akses Ditolak';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: title,
                            text: message,
                            timer: 1800,
                            timerProgressBar: true,
                            confirmButtonText: 'OK'
                        });
                    },

                    complete: function() {

                        resetScanner();
                    }
                });
            }


            /*
            |--------------------------------------------------------------------------
            | RESET SCANNER
            |--------------------------------------------------------------------------
            */

            function resetScanner() {

                clearTimeout(scanTimer);

                scanBuffer = '';
                lastKeyTime = 0;
                processingScan = false;

                barcode
                    .val('')
                    .prop('disabled', false)
                    .prop('readonly', true);

                focusBarcode();
            }


            /*
            |--------------------------------------------------------------------------
            | OUTLET CHANGE
            |--------------------------------------------------------------------------
            */

            outlet.on('change', function() {

                const outletId = $(this).val();

                scanBuffer = '';
                lastKeyTime = 0;

                barcode.val('');

                if (!outletId) {

                    barcode.prop('disabled', true);

                    historyOutlet.text('-');

                    loadHistory();

                    return;
                }

                const outletText = $(this)
                    .find('option:selected')
                    .text()
                    .trim();

                historyOutlet.text(outletText);

                barcode
                    .prop('disabled', false)
                    .prop('readonly', true)
                    .focus();

                loadHistory();
            });


            /*
            |--------------------------------------------------------------------------
            | BARCODE SCANNER LISTENER
            |--------------------------------------------------------------------------
            |
            | Scanner USB/Bluetooth akan mengirim:
            |
            | 1 -> 0 -> 3 -> 5 -> 0 -> 1 -> ENTER
            |
            | ENTER tidak ikut dikirim ke server.
            | \r dan \n juga dibersihkan.
            |
            */

            $(document).on('keydown', function(e) {

                if (!outlet.val() || processingScan) {
                    return;
                }

                /*
                |--------------------------------------------------------------------------
                | IGNORE MODIFIER
                |--------------------------------------------------------------------------
                */

                if (
                    e.key === 'Shift' ||
                    e.key === 'Control' ||
                    e.key === 'Alt' ||
                    e.key === 'Meta' ||
                    e.key === 'Tab' ||
                    e.key === 'Escape'
                ) {
                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | ENTER = SCANNER SELESAI
                |--------------------------------------------------------------------------
                */

                if (e.key === 'Enter') {

                    e.preventDefault();
                    e.stopPropagation();

                    clearTimeout(scanTimer);

                    const qrcode = cleanBarcode(scanBuffer);

                    scanBuffer = '';
                    lastKeyTime = 0;

                    barcode.val('');

                    if (qrcode.length >= MIN_BARCODE_LENGTH) {
                        processScan(qrcode);
                    }

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | HANYA TERIMA KARAKTER
                |--------------------------------------------------------------------------
                */

                if (e.key.length !== 1) {
                    return;
                }

                const now = Date.now();


                /*
                |--------------------------------------------------------------------------
                | DETEKSI KECEPATAN SCANNER
                |--------------------------------------------------------------------------
                */

                if (
                    lastKeyTime > 0 &&
                    (now - lastKeyTime) > SCANNER_MAX_INTERVAL
                ) {

                    /*
                    | Jeda terlalu lama = kemungkinan ketikan manual.
                    | Buang buffer.
                    */

                    scanBuffer = '';
                }

                lastKeyTime = now;

                scanBuffer += e.key;

                /*
                |--------------------------------------------------------------------------
                | TAMPILKAN HASIL SCAN
                |--------------------------------------------------------------------------
                */

                barcode.val(scanBuffer);

                /*
                |--------------------------------------------------------------------------
                | AUTO RESET
                |--------------------------------------------------------------------------
                */

                clearTimeout(scanTimer);

                scanTimer = setTimeout(function() {

                    scanBuffer = '';
                    lastKeyTime = 0;

                    barcode.val('');

                }, SCANNER_TIMEOUT);

            });


            /*
            |--------------------------------------------------------------------------
            | BLOK KEYBOARD MANUAL PADA INPUT
            |--------------------------------------------------------------------------
            */

            barcode.on('keydown keypress keyup', function(e) {

                /*
                | Scanner ditangkap oleh document listener di atas.
                | Input readonly sehingga keyboard manual tidak dapat memasukkan teks.
                */

                if (e.key !== 'Tab') {
                    e.preventDefault();
                }

            });


            /*
            |--------------------------------------------------------------------------
            | CLICK PAGE = FOCUS SCANNER
            |--------------------------------------------------------------------------
            */

            $(document).on('click', function(e) {

                if (
                    $(e.target).closest('#outlet_id').length ||
                    $(e.target).closest('.swal2-container').length
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

            barcode
                .prop('disabled', !outlet.val())
                .prop('readonly', true);

            if (outlet.val()) {

                loadHistory();
                focusBarcode();
            }

        });
    </script>
@endpush
