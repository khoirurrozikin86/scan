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

                        <div id="outletError" class="text-danger small mt-2">
                        </div>

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

        <div id="scanResult" class="card mt-4 d-none">

            <div class="card-body">

                <h5 class="mb-3">

                    <i data-feather="check-circle"></i>

                    Hasil Scan

                </h5>


                <div id="scanResultContent">
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

                const outlet =
                    document.getElementById(
                        'outlet_id'
                    );

                const btnStart =
                    document.getElementById(
                        'btnStartCamera'
                    );

                const btnStop =
                    document.getElementById(
                        'btnStopCamera'
                    );

                const video =
                    document.getElementById(
                        'cameraPreview'
                    );

                const status =
                    document.getElementById(
                        'cameraStatus'
                    );


                let stream = null;


                /*
                |--------------------------------------------------------------------------
                | Feather
                |--------------------------------------------------------------------------
                */

                if (window.feather) {

                    feather.replace();

                }


                /*
                |--------------------------------------------------------------------------
                | START CAMERA
                |--------------------------------------------------------------------------
                */

                btnStart.addEventListener(
                    'click',
                    async function() {

                        if (!outlet.value) {

                            Swal.fire({
                                icon: 'warning',
                                title: 'Pilih Outlet',
                                text: 'Silakan pilih outlet terlebih dahulu.'
                            });

                            return;

                        }


                        try {

                            stream =
                                await navigator.mediaDevices
                                .getUserMedia({
                                    video: {
                                        facingMode: {
                                            ideal: 'environment'
                                        }
                                    },
                                    audio: false
                                });


                            video.srcObject = stream;


                            btnStart.classList.add(
                                'd-none'
                            );

                            btnStop.classList.remove(
                                'd-none'
                            );


                            status.innerHTML =
                                '<span class="text-success">' +
                                '<i data-feather="camera"></i> ' +
                                'Camera aktif. Arahkan QR Code ke kamera.' +
                                '</span>';


                            if (window.feather) {
                                feather.replace();
                            }


                            /*
                             * Nanti QR scanner kita
                             * sambungkan di sini.
                             */

                        } catch (error) {

                            console.error(error);


                            Swal.fire({
                                icon: 'error',
                                title: 'Camera Tidak Bisa Dibuka',
                                text: 'Pastikan browser memiliki izin menggunakan kamera.'
                            });

                        }

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | STOP CAMERA
                |--------------------------------------------------------------------------
                */

                btnStop.addEventListener(
                    'click',
                    function() {

                        stopCamera();

                    }
                );


                function stopCamera() {

                    if (stream) {

                        stream
                            .getTracks()
                            .forEach(
                                track => track.stop()
                            );

                        stream = null;

                    }


                    video.srcObject = null;


                    btnStart.classList.remove(
                        'd-none'
                    );

                    btnStop.classList.add(
                        'd-none'
                    );


                    status.innerHTML =
                        'Camera berhenti.';

                }


                /*
                |--------------------------------------------------------------------------
                | OUTLET CHANGE
                |--------------------------------------------------------------------------
                */

                outlet.addEventListener(
                    'change',
                    function() {

                        if (!this.value) {

                            stopCamera();

                            status.innerHTML =
                                'Pilih outlet terlebih dahulu.';

                            return;

                        }


                        status.innerHTML =
                            'Outlet dipilih. Silakan mulai camera.';

                    }
                );


                /*
                |--------------------------------------------------------------------------
                | CLEANUP
                |--------------------------------------------------------------------------
                */

                window.addEventListener(
                    'beforeunload',
                    function() {

                        stopCamera();

                    }
                );

            }

        );
    </script>
@endpush
