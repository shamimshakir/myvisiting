@extends('master')

@section('title')
Users
@endsection

@section('content')
<div class="card shadow mb-4 position-relative" id="users">
    <div class="ajax-loading"><img src="{{ asset('app_files/public/images/loader/loader2.gif') }}" /></div>
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">All Users</h6>
        <?php 
        $res = App\Http\Controllers\PermissionController::getNodePermissionByProfile('users');
        if($res['store'] == $res->route->id ){ 
        ?>
        <button id="add-user-button" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add User
        </button>
        <?php } ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th>Image</th>
                        <th>Name</th>
                         <th>Profile</th>
                        <th>Email/UserID</th>
                        <th>Phone</th>
                        <th>Designation</th>
                        <th>Employment</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="results"></tbody>
            </table>
        </div>

        <!-- User Add Modal-->
        <div class="modal" data-easein="flipYIn" id="addUserModal" tabindex="-1" role="dialog"
            aria-labelledby="userModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="userModal">Add User</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('users.store') }}" method="post" id="addUserForm"
                            enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Name">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="phone">Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" id="phone"
                                            placeholder="Phone">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Email As UserID <span class="text-danger">*</span></label>
                                        <input type="text" name="email" class="form-control" id="email"
                                            placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="department_id">Department</label>
                                        <select class="form-control" name="department_id" class="form-control"
                                            id="department_id">
                                            <option value="">Select Department</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="designation_id">Designation</label>
                                        <select class="form-control" name="designation_id" class="form-control"
                                            id="designation_id">
                                            <option value="">Select Designation</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="emp_status">Employment Status</label>
                                        <select class="form-control" name="emp_status" class="form-control"
                                            id="emp_status">
                                            <option value="">Select Status</option>
                                            <option value="Permanent">Permanent</option>
                                            <option value="Temporary">Temporary</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="country_id">Country</label>
                                        <select class="form-control" name="country_id" class="form-control"
                                            id="country_id">
                                            <option value="">Select Country</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="city_id">City</label>
                                        <select class="form-control" name="city_id" class="form-control" id="city_id">
                                            <option value="">Select City</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="thana_id">Thana</label>
                                        <select class="form-control" name="thana_id" class="form-control" id="thana_id">
                                            <option value="">Select Thana</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" name="address" class="form-control" id="address"
                                            placeholder="Address">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="profile_id">Profile <span class="text-danger">*</span></label>
                                        <select class="form-control" name="profile_id" class="form-control"
                                            id="profile_id">
                                            <option value="">Select Profile</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="text" name="password" class="form-control" id="password"
                                            placeholder="Password">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="activity">Active Status</label>
                                        <select class="form-control" name="activity" class="form-control"
                                            id="activity">
                                            <option value="">Select Status</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="photo">Photo</label>
                                        <div class="custom-file">
                                            <input type="file" name="photo" accept="image/*" class="custom-file-input"
                                                id="photo" onchange="loadFile(event, 'addUserModal')">
                                            <label class="custom-file-label" for="photo">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <img id="output" />
                                </div>
                            </div>
                            <div class="buttons d-flex align-items-center justify-content-end">
                                <button class="btn btn-secondary btn-sm mr-2" type="button"
                                    data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Edit Modal-->
        <div class="modal" data-easein="flipYIn" id="editUserModal" tabindex="-1" role="dialog"
            aria-labelledby="userModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="userModal">Edit User</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="editUserForm" enctype="multipart/form-data">
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="name">Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name"
                                            placeholder="Name">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="phone">Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="phone" class="form-control" id="phone"
                                            placeholder="Phone">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Email As UserID <span class="text-danger">*</span></label>
                                        <input type="text" name="email" class="form-control" id="email"
                                            placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="department_id">Department</label>
                                        <select class="form-control" name="department_id" class="form-control"
                                            id="department_id">
                                            <option value="">Select Department</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="designation_id">Designation</label>
                                        <select class="form-control" name="designation_id" class="form-control"
                                            id="designation_id">
                                            <option value="">Select Designation</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="emp_status">Employment Status</label>
                                        <select class="form-control" name="emp_status" class="form-control"
                                            id="emp_status">
                                            <option value="">Select Status</option>
                                            <option value="Permanent">Permanent</option>
                                            <option value="Temporary">Temporary</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="country_id">Country</label>
                                        <select class="form-control" name="country_id" class="form-control"
                                            id="country_id">
                                            <option value="">Select Country</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="city_id">City</label>
                                        <select class="form-control" name="city_id" class="form-control" id="city_id">
                                            <option value="">Select City</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="thana_id">Thana</label>
                                        <select class="form-control" name="thana_id" class="form-control" id="thana_id">
                                            <option value="">Select Thana</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="address">Address</label>
                                        <input type="text" name="address" class="form-control" id="address"
                                            placeholder="Address">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="profile_id">Profile <span class="text-danger">*</span></label>
                                        <select class="form-control" name="profile_id" class="form-control"
                                            id="profile_id">
                                            <option value="">Select Profile</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="activity">Active Status</label>
                                        <select class="form-control" name="activity" class="form-control"
                                            id="activity">
                                            <option value="">Select Status</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="photo">Photo</label>
                                        <div class="custom-file">
                                            <input type="file" name="photo" accept="image/*" class="custom-file-input"
                                                id="photo" onchange="loadFile(event, 'editUserModal')">
                                            <label class="custom-file-label" for="photo">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>
                                            <!-- <p style="font-size: 12px; margin-bottom: 0; color: #0202bb;">Current</p> -->
                                            <img id="output" />
                                        </div>
                                        <div>
                                            <p style="font-size: 12px; margin-bottom: 0; color: #0202bb;">
                                                Previous
                                            </p>
                                            <img id="previousImg" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="buttons d-flex align-items-center justify-content-end">
                                <button class="btn btn-secondary btn-sm mr-2" type="button"
                                    data-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@section('footer-scripts')
<script>
$(document).ready(function() {

    $("#addUserForm").validate({
        rules: {
            name: {
                required: true,
                maxlength: 50,
            },
            email: {
                required: true,
                email: true,
            },
            phone: {
                required: true
            },
            password: {
                required: true
            },
            profile_id: {
                required: true
            },
        },
        messages: {
            name: {
                required: "Name is required",
                maxlength: "Name cannot be more than 50 characters"
            },
            email: {
                required: "Email is required",
            },
            phone: {
                required: "Phone is required"
            },
            password: {
                required: "Password is required"
            },
            profile_id: {
                required: "Profile is required"
            },
        }
    });
    $("#editUserForm").validate({
        rules: {
            name: {
                required: true,
                maxlength: 50,
            },
            email: {
                required: true,
                email: true,
            },
            phone: {
                required: true
            },
            profile_id: {
                required: true
            },
        },
        messages: {
            name: {
                required: "Name is required",
                maxlength: "Name cannot be more than 50 characters"
            },
            email: {
                required: "Email is required",
            },
            phone: {
                required: "Phone is required"
            },
            profile_id: {
                required: "Profile is required"
            },
        }
    });

    $(document).on("click", '#edit-user-button', function(event) {
        let id = $(this).attr("data-id");
        get_datas(`users/${id}`).then(res => {
            $('#editUserModal').attr("data-id", id);
            $('#editUserForm #name').val(res.name);
            $('#editUserForm #phone').val(res.phone);
            $('#editUserForm #email').val(res.email);
            $('#editUserModal #previousImg').attr('src', '/app_files/public/images/users/' + res.photo);
            $('#editUserForm #address').val(res.address);

            $('#editUserForm #address').val(res.address);

            $('#editUserForm #country_id').empty();
            $('#editUserForm #department_id').empty();
            $('#editUserForm #profile_id').empty();
            $('#editUserForm #designation_id').empty();
            $('#editUserForm #city_id').empty();
            $('#editUserForm #thana_id').empty();

            let countries_p = get_datas('/get_countries');
            let departments_p = get_datas('/get_departments');
            let profiles_p = get_datas('/get_profiles');
            let designations_p = get_datas('/get_designations');
            let cities_p = get_datas('/get_cities');
            let thanas_p = get_datas('/get_thanas');
            Promise.all([countries_p, departments_p, profiles_p, designations_p, cities_p,
                thanas_p
            ]).then((
                values) => {
                $('#editUserForm #country_id').append(
                    `<option value="">Select Country</option>`);
                values[0].forEach(el => {
                    let option = `<option value="${el.id}">${el.name}</option>`;
                    $('#editUserForm #country_id').append(option);
                });
                $('#editUserForm #department_id').append(
                    `<option value="">Select Department</option>`);
                values[1].forEach(el => {
                    let option = `<option value="${el.id}">${el.name}</option>`;
                    $('#editUserForm #department_id').append(option);
                });
                $('#editUserForm #profile_id').append(
                    `<option value="">Select Profile</option>`);
                values[2].forEach(el => {
                    let option = `<option value="${el.id}">${el.name}</option>`;
                    $('#editUserForm #profile_id').append(option);
                });
                $('#editUserForm #designation_id').append(
                    `<option value="">Select Designation</option>`);
                values[3].forEach(el => {
                    let option = `<option value="${el.id}">${el.name}</option>`;
                    $('#editUserForm #designation_id').append(option);
                });
                $('#editUserForm #city_id').append(
                    `<option value="">Select City</option>`);
                values[4].forEach(el => {
                    let option = `<option value="${el.id}">${el.name}</option>`;
                    $('#editUserForm #city_id').append(option);
                });
                $('#editUserForm #thana_id').append(
                    `<option value="">Select Thana</option>`);
                values[5].forEach(el => {
                    let option = `<option value="${el.id}">${el.name}</option>`;
                    $('#editUserForm #thana_id').append(option);
                });

                makeOptionSelected('editUserForm', 'country_id', res.country_id);
                makeOptionSelected('editUserForm', 'department_id', res.department_id);
                makeOptionSelected('editUserForm', 'profile_id', res.profile_id);
                makeOptionSelected('editUserForm', 'designation_id', res
                    .designation_id);
                makeOptionSelected('editUserForm', 'city_id', res.city_id);
                makeOptionSelected('editUserForm', 'thana_id', res.thana_id);
                makeOptionSelected('editUserForm', 'emp_status', res.emp_status);
                makeOptionSelected('editUserForm', 'activity', res.activity);

                $('#editUserModal').modal('show');
            });
        });
    });


    $("#add-user-button").click(function() {
        $('#addUserForm #country_id').empty();
        $('#addUserForm #department_id').empty();
        $('#addUserForm #profile_id').empty();
        $('#addUserForm #designation_id').empty();
        $('#addUserForm #city_id').empty();
        $('#addUserForm #thana_id').empty();
        let countries_p = get_datas('/get_countries');
        let departments_p = get_datas('/get_departments');
        let profiles_p = get_datas('/get_profiles');
        let designations_p = get_datas('/get_designations');
        Promise.all([countries_p, departments_p, profiles_p, designations_p]).then((values) => {
            $('#addUserForm #country_id').append(`<option value="">Select Country</option>`);
            values[0].forEach(el => {
                let option = `<option value="${el.id}">${el.name}</option>`;
                $('#addUserForm #country_id').append(option);
            });
            $('#addUserForm #department_id').append(
                `<option value="">Select Department</option>`);
            values[1].forEach(el => {
                let option = `<option value="${el.id}">${el.name}</option>`;
                $('#addUserForm #department_id').append(option);
            });
            $('#addUserForm #profile_id').append(
                `<option value="">Select Profile</option>`);
            values[2].forEach(el => {
                let option = `<option value="${el.id}">${el.name}</option>`;
                $('#addUserForm #profile_id').append(option);
            });
            $('#addUserForm #designation_id').append(
                `<option value="">Select Designation</option>`);
            values[3].forEach(el => {
                let option = `<option value="${el.id}">${el.name}</option>`;
                $('#addUserForm #designation_id').append(option);
            });
            $('#addUserModal').modal('show');
        });
    });
    $('#addUserForm #country_id').on('change', function(e) {
        let country_id = e.target.value;
        let cities_p = get_datas(`/get_cities_by_id/${country_id}`);
        cities_p.then(res => {
            $('#addUserForm #city_id').empty();
            $('#addUserForm #city_id').append(`<option value="">Select City</option>`);
            res.forEach(el => {
                let option = `<option value="${el.id}">${el.name}</option>`;
                $('#addUserForm #city_id').append(option);
            });
        })
    });
    $('#addUserForm #city_id').on('change', function(e) {
        let city_id = e.target.value;
        let cities_p = get_datas(`/get_thanas_by_id/${city_id}`);
        cities_p.then(res => {
            $('#addUserForm #thana_id').empty();
            $('#addUserForm #thana_id').append(`<option value="">Select Thana</option>`);
            res.forEach(el => {
                let option = `<option value="${el.id}">${el.name}</option>`;
                $('#addUserForm #thana_id').append(option);
            });
        })
    });
    $('#editUserForm #country_id').on('change', function(e) {
        let country_id = e.target.value;
        let cities_p = get_datas(`/get_cities_by_id/${country_id}`);
        cities_p.then(res => {
            $('#editUserForm #city_id').empty();
            $('#editUserForm #city_id').append(`<option value="">Select City</option>`);
            res.forEach(el => {
                let option = `<option value="${el.id}">${el.name}</option>`;
                $('#editUserForm #city_id').append(option);
            });
        })
    });
    $('#editUserForm #city_id').on('change', function(e) {
        let city_id = e.target.value;
        let cities_p = get_datas(`/get_thanas_by_id/${city_id}`);
        cities_p.then(res => {
            $('#editUserForm #thana_id').empty();
            $('#editUserForm #thana_id').append(`<option value="">Select Thana</option>`);
            res.forEach(el => {
                let option = `<option value="${el.id}">${el.name}</option>`;
                $('#editUserForm #thana_id').append(option);
            });
        })
    });
    $('#addUserForm').submit(function(e) {
        e.preventDefault();
        let store_url = $("#addUserForm").attr('action');
        let formData = new FormData(this);
        if ($("#addUserForm").valid()) {
            $.ajax({
                url: store_url,
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) { 
                    toastr.success(data.message)
                    $('#addUserModal').modal('hide');
                    $('#addUserForm').trigger("reset");
                    load_users();
                },
                error: function(err) {
                    console.dir(err)
                    toastr.error('Failed to save!')
                }
            })
        }
    });
    $('#editUserForm').submit(function(e) {
        e.preventDefault();
        let id = $('#editUserModal').attr("data-id");
        let formData = new FormData(this);
        if ($("#editUserForm").valid()) {
            $.ajax({
                url: "/users/" + id,
                method: "post",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    toastr.success(data.message)
                    $('#editUserModal').modal('hide');
                    $('#editUserModal').trigger("reset");
                    load_users();
                },
                error: function(err) {
                    console.dir(err)
                    toastr.error('Failed to update!')
                }
            })
        }
    });

    function load_users() {
        $.ajax({
                url: "get_user_list",
                type: "get",
                datatype: "html",
                beforeSend: function() {
                    $('.ajax-loading').show();
                }
            })
            .done(function(data) {
                if (data.length == 0) {
                    $('.ajax-loading').html("No more records!");
                    return;
                }
                $('.ajax-loading').hide();
                $("#results").empty().append(data); 
            })
            .fail(function(err) {
                alert('No response from server');
            });
    }
    load_users();
});
</script>
@endsection