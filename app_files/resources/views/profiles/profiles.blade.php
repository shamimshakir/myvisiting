@extends('master')

@section('title')
Profiles
@endsection

@section('content')
<div class="card shadow mb-4 position-relative">
    <div class="ajax-loading"><img src="{{ asset('app_files/public/images/loader/loader1.gif') }}" /></div>
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">All Profile</h6>
        <?php 
        // $res = App\Http\Controllers\PermissionController::getNodePermissionByProfile('profiles');
        // if($res['store'] == $res->route->id ){ 
        ?>
        <button id="add_profile_button" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add Profile
        </button>
        <?php // } ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="8%">SL</th>
                        <th>Level</th>
                        <th>Profile Name</th>
                        <th>Status</th>
                        <th width="20%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="results"></tbody>
            </table>
        </div>

        <!-- Profile Add Modal -->
        <div class="modal" data-easein="shrinkIn" id="addProfileModal" tabindex="-1" role="dialog"
            aria-labelledby="profileModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="profileModal">Add Profile</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('profiles.store') }}" method="post" id="addProfileForm">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="activity">Status</label>
                                        <select class="form-control" id="activity" name="activity">
                                          <option value="">Select Status</option>
                                          <option value="active">Active</option> 
                                          <option value="inactive">Inactive</option> 
                                        </select>
                                    </div> 
                                </div> 
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="level_sl">Level (SL)</label>
                                        <input type="number" name="level_sl" class="form-control" id="level_sl" placeholder="Level">
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

        <!-- Profile Edit Modal -->
        <div class="modal" data-easein="shrinkIn" id="editProfileModal" tabindex="-1" role="dialog"
            aria-labelledby="profileModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="profileModal">Edit Profile</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="editProfileForm">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                                    </div>
                                </div> 
                            </div>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="activity">Status</label>
                                        <select class="form-control" id="activity" name="activity">
                                          <option value="">Select Status</option>
                                          <option value="active">Active</option> 
                                          <option value="inactive">Inactive</option> 
                                        </select>
                                    </div> 
                                </div> 
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="level_sl">Level (SL)</label>
                                        <input type="number" name="level_sl" class="form-control" id="level_sl" placeholder="Level">
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

        <!-- Permisstion Modal -->
        <div class="modal" data-easein="flipYIn" id="permissionModal" tabindex="-1" role="dialog"
            aria-labelledby="perModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="perModal">Permisstion for
                            <span id="profile-name"></span>
                        </h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="permissionForm">
                            <table class="table table-bordered">
                                <thead>
                                    <th>Node/Route</th>
                                    <th>View</th>
                                    <th>Add</th>
                                    <th>Edit</th>
                                </thead>
                                <tbody id="permission_results">
                                </tbody>
                            </table>
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
    $("#addProfileForm").validate({
        rules: {
            name: {
                required: true,
                maxlength: 50,
            },
            level_sl: {
                required: true,  
            },
        },
        messages: {
            name: {
                required: "Name is required",
                maxlength: "Name cannot be more than 50 characters"
            },
            level_sl: {
                required: "Level is required", 
            },
        }
    });
    $("#editProfileForm").validate({
        rules: {
            name: {
                required: true,
                maxlength: 50,
            },
            level_sl: {
                required: true,  
            },
        },
        messages: {
            name: {
                required: "Name is required",
                maxlength: "Name cannot be more than 50 characters"
            },
            level_sl: {
                required: "Level is required", 
            },
        }
    });

    function load_profiles() {
        $.ajax({
                url: "get_profile_list",
                type: "get",
                datatype: "html",
                beforeSend: function() {
                    $('.ajax-loading').show();
                }
            })
            .done(function(data) {
                // console.dir(data)
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
    load_profiles();
    
    
    $(document).on("click", '#add_profile_button', function(event) {
        get_datas(`log_user_level`).then(res => { 
            $("#addProfileModal #level_sl").attr("min", res);
            $( "#addProfileModal #level_sl" ).rules( "add", {
              min: res
            });
            $('#addProfileModal').modal('show'); 
        });
        
    });
    

    $(document).on("click", '#edit-profile-button', function(event) {
        let id = $(this).attr("data-id");
        
        let profile_p = get_datas(`profiles/${id}`);
        let level_p = get_datas(`log_user_level`);
        Promise.all([profile_p, level_p]).then(values => {
            $('#editProfileModal #name').val(values[0].name);
            $('#editProfileModal #level_sl').val(values[0].level_sl);
            $('#editProfileModal').attr("data-id", id);
            makeOptionSelected('editProfileModal', 'activity', values[0].activity);
            
            $("#editProfileModal #level_sl").attr("min", values[1]);
            $( "#editProfileModal #level_sl" ).rules( "add", {
              min: values[1]
            });
            $('#editProfileModal').modal('show');
        });
    });


    $(document).on("click", '#permission-button', function(event) {
        let profile_id = $(this).attr("data-id");
        get_datas(`profiles/${profile_id}`).then(res => {
            // console.dir(res)
            $('#permissionModal #profile-name').text(res.name);
            $('#permissionModal').attr("data-id", profile_id);
            $("#permission_results").empty();
            
 
            let nodes_p = get_datas('/get_nodes');
            Promise.all([nodes_p]).then((values) => {
                // console.dir(values[0])
                values[0].forEach((el, elIndex) => {
                    let checkView = "",
                        checkStore = "",
                        checkEdit = "";
                    if (res.permissions[elIndex] && el.id == res
                        .permissions[elIndex].view) {
                        checkView = "checked";
                    }
                    if (res.permissions[elIndex] && el.id == res
                        .permissions[elIndex].store) {
                        checkStore = "checked";
                    }
                    if (res.permissions[elIndex] && el.id == res
                        .permissions[elIndex].edit) {
                        checkEdit = "checked";
                    }
                    let row = `<tr>
                    <td>
                        ${el.node_name}
                    </td>`;
                    if(el.viewing){
                    row += `<td>
                        <div class="form-group">
                            <div class="form-check">
                                <input name="${el.route_name}" class="form-check-input" type="checkbox" value="${el.id}" ${checkView}>
                            </div>
                        </div>
                    </td>`;
                    }
                    if(el.adding){
                    row += `<td>
                        <div class="form-group">
                            <div class="form-check">
                                <input name="${el.route_name}" class="form-check-input" type="checkbox" value="${el.id}" ${checkStore}>
                            </div>
                        </div>
                    </td>`;
                    }
                    if(el.editing){
                    row += `<td>
                            <div class="form-group">
                                <div class="form-check">
                                    <input name="${el.route_name}" class="form-check-input" type="checkbox" value="${el.id}" ${checkEdit}>
                                </div>
                            </div>
                        </td>
                    </tr>`;
                    }
                    $("#permission_results").append(row);
                });

                $('#permissionModal').modal('show');
            });
            
        });
    });

    $('#permissionForm').submit(function(e) {
        e.preventDefault();
        let node_checks = {};
        let profile_id = $("#permissionModal").attr("data-id");
        let checkboxes = $("#permissionForm input:checkbox");
        $("#permissionForm input:checkbox").each(function() {
            let val = $(this).val();
            if (!(val in node_checks)) {
                node_checks[val] = [];
            }
            if ($(this).is(":checked")) {
                node_checks[val].push(val);
            } else {
                node_checks[val].push(0);
            }
        });
        $.ajax({
            url: '/permission/save',
            type: "POST",
            data: {
                nodes: node_checks,
                profile_id: profile_id
            },
            dataType: 'json',
            success: function(res) {
                console.dir(res)
                toastr.success(res.message)
                $(`#permissionModal`).modal('hide');
                $(`#permissionForm`).trigger("reset");
            },
            error: function(err) {
                console.dir(err)
                toastr.error('Failed to save!')
            }
        });
    });


    $('#addProfileForm').submit(function(e) {
        e.preventDefault();
        var values = {
            name: $("#addProfileForm #name").val(),
            activity: $("#addProfileForm #activity option:selected").val(),
            level_sl: $("#addProfileForm #level_sl").val(), 
        };
        let store_url = $("#addProfileForm").attr('action');
        if ($("#addProfileForm").valid()) {
            $.ajax({
                url: store_url,
                type: "POST",
                data: values,
                dataType: 'json',
                success: function(data) { 
                    toastr.success(data.message)
                    $(`#addProfileModal`).modal('hide');
                    $(`#addProfileForm`).trigger("reset");
                    load_profiles();
                },
                error: function(err) { 
                    console.log(err)
                    toastr.error('Failed to save!')
                }
            });
        }
    });

    $('#editProfileForm').submit(function(e) {
        let id = $('#editProfileModal').attr("data-id");
        e.preventDefault();
        var values = {
            name: $("#editProfileForm #name").val(),
            activity: $("#editProfileForm #activity option:selected").val(),
            level_sl: $("#editProfileForm #level_sl").val(), 
        };
        if ($("#editProfileForm").valid()) {
            $.ajax({
                url: "/profiles/" + id,
                type: "PUT",
                data: values,
                dataType: 'json',
                success: function(data) { 
                    console.log(data)
                    toastr.success(data.message)
                    $(`#editProfileModal`).modal('hide');
                    $(`#editProfileForm`).trigger("reset");
                    load_profiles();
                },
                error: function(err) { 
                    console.log(err)
                    toastr.error('Failed to update!')
                }
            });
        }
    });
});
</script>
@endsection