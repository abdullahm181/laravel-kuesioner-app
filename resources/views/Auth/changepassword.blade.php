@extends('layout.app')

@section('title')
    Change Password
@endsection

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Change Password</h1>
    </div>

    <div class="row">
        <div class="col-xl-12 col-md-12">
            <!-- DataTales Example -->
            <div class="card shadow mb-4">
                <div class="card-body">
                    <form id="formChangePassword" method="POST" action="javascript:void(0);">
                        <div class="form-group" id='formGroupPassword'>
                            <label for="oldpassword">Password Lama</label>
                            <input type="password" class="form-control form-control-user" id="oldpassword"
                                name="oldpassword" placeholder="Password">
                        </div>
                        <div class="form-group" id='formGroupPassword'>
                            <label for="password">Password Baru</label>
                            <input type="password" class="form-control form-control-user" id="password" name="password"
                                placeholder="Password">
                        </div>
                        <div class="form-group" id='formGroupPasswordRe'>
                            <label for="password_confirmation">Komfirmasi Password Baru</label>
                            <input type="password" class="form-control form-control-user" id="password_confirmation"
                                name="password_confirmation" placeholder="Repeat Password">
                        </div>
                        <button type="submit" class="btn btn-block btn-outline-primary mt-3" id="form-data-submit-btn">
                            Save data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="text/javascript">
        $(document).ready(function() {
            document.getElementById('formChangePassword').reset();
            $("#formChangePassword").on("submit", function(event) {
                event.preventDefault();

                $('#loader_page').addClass('show');
                let data = $("#formChangePassword").serializeArray().reduce(function(obj, item) {
                    obj[item.name] = item.value;
                    return obj;
                }, {});
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ url('/storeNewPassword') }}',
                    type: "POST",
                    data: data,
                    success: function(response) {
                        document.getElementById('formChangePassword').reset();
                        if (response.status) {
                            Swal.fire({
                                title: "Success change password!",
                                text: "",
                                icon: "success"
                            });
                        } else {
                            Swal.fire({
                                icon: "error",
                                title: "Error Save Data",
                                text: response.message,
                            });
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                }).always(function() {
                    $('#loader_page').removeClass('show');
                });
            })
        });
    </script>
@endsection
