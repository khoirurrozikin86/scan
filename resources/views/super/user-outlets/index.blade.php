@extends('layouts.admin')

@section('title', 'User Outlet Access')

@section('content')

    <div class="row">
        <div class="col-md-12">

            <div class="card">

                <div class="card-header">
                    <h5 class="mb-0">
                        User Outlet Access
                    </h5>
                </div>

                <div class="card-body">

                    <div class="mb-4">

                        <label class="form-label">
                            User
                        </label>

                        <select id="user_id" class="form-select">

                            <option value="">
                                -- Pilih User --
                            </option>

                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                    -
                                    {{ $user->email }}
                                </option>
                            @endforeach

                        </select>

                    </div>


                    <div id="outletContainer" style="display:none;">

                        <label class="form-label">
                            Outlet Access
                        </label>

                        <div class="row">

                            @foreach ($outlets as $outlet)
                                <div class="col-md-4 mb-2">

                                    <div class="form-check">

                                        <input class="form-check-input outlet-check" type="checkbox"
                                            value="{{ $outlet->id }}" id="outlet_{{ $outlet->id }}">

                                        <label class="form-check-label" for="outlet_{{ $outlet->id }}">
                                            {{ $outlet->outlet_name }}
                                        </label>

                                    </div>

                                </div>
                            @endforeach

                        </div>


                        <div class="mt-4">

                            <button type="button" id="btnSave" class="btn btn-primary">
                                Save Access
                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>
    </div>

@endsection




@push('scripts')
    <script>
        $(function() {

            const userSelect =
                $('#user_id');

            const outletContainer =
                $('#outletContainer');

            const btnSave =
                $('#btnSave');


            /*
             * PILIH USER
             */
            userSelect.on(
                'change',
                function() {

                    const userId =
                        $(this).val();

                    $('.outlet-check')
                        .prop('checked', false);

                    if (!userId) {

                        outletContainer.hide();

                        return;
                    }


                    $.ajax({

                        url: `/super/user-outlets/${userId}/edit`,

                        type: 'GET',

                        success: function(res) {

                            const outletIds =
                                res.outlet_ids || [];

                            outletIds.forEach(
                                function(id) {

                                    $(
                                        '#outlet_' + id
                                    ).prop(
                                        'checked',
                                        true
                                    );

                                }
                            );

                            outletContainer.show();
                        },

                        error: function() {

                            Swal.fire(
                                'Error',
                                'Gagal mengambil outlet user.',
                                'error'
                            );

                        }

                    });

                }
            );


            /*
             * SAVE
             */
            btnSave.on(
                'click',
                function() {

                    const userId =
                        userSelect.val();

                    if (!userId) {

                        Swal.fire(
                            'Perhatian',
                            'Pilih user terlebih dahulu.',
                            'warning'
                        );

                        return;
                    }


                    const outletIds =
                        $('.outlet-check:checked')
                        .map(function() {

                            return $(this).val();

                        })
                        .get();


                    $.ajax({

                        url: `/super/user-outlets/${userId}`,

                        type: 'PUT',

                        data: {

                            user_id: userId,

                            outlet_ids: outletIds,

                            _token: $('meta[name="csrf-token"]')
                                .attr('content'),

                        },

                        success: function(res) {

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: res.message,
                                timer: 1500,
                                showConfirmButton: false
                            });

                        },

                        error: function(xhr) {

                            let message =
                                'Gagal menyimpan outlet access.';

                            if (
                                xhr.responseJSON &&
                                xhr.responseJSON.message
                            ) {
                                message =
                                    xhr.responseJSON.message;
                            }

                            Swal.fire(
                                'Error',
                                message,
                                'error'
                            );

                        }

                    });

                }
            );

        });
    </script>
@endpush
