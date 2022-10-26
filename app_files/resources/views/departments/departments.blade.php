@extends('master')

@section('title')
Departments
@endsection

@section('content')
<div class="card shadow mb-4 position-relative">
    <div class="ajax-loading"><img src="{{ asset('app_files/public/images/loader/loader1.gif') }}" /></div>
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">All Departments</h6>
        <?php  
        $res = App\Http\Controllers\PermissionController::getNodePermissionByProfile('departments');
        if($res['store'] == $res->route->id ){  
        ?>
        <button data-toggle="modal" data-target="#addDepartmentModal" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add Department
        </button>
        <?php } ?>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="8%">SL</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="results"></tbody>
            </table>
        </div>
        <!-- Department Add Modal -->
        <div class="modal" data-easein="swing" id="addDepartmentModal" tabindex="-1" role="dialog"
            aria-labelledby="departmentModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="departmentModal">Add Department
                        </h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('departments.store') }}" method="post" id="addDepartmentForm">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="activity">Status</label>
                                <select class="form-control" id="activity" name="activity">
                                  <option value="">Select Status</option>
                                  <option value="active">Active</option> 
                                  <option value="inactive">Inactive</option> 
                                </select>
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

        <!-- Department Edit Modal -->
        <div class="modal" data-easein="swing" id="editDepartmentModal" tabindex="-1" role="dialog"
            aria-labelledby="departmentModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="departmentModal">Edit Department
                        </h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="editDepartmentForm">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label for="activity">Status</label>
                                <select class="form-control" id="activity" name="activity">
                                  <option value="">Select Status</option>
                                  <option value="active">Active</option> 
                                  <option value="inactive">Inactive</option> 
                                </select>
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
    $("#addDepartmentForm").validate({
        rules: {
            name: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Name is required",
            },
        }
    });

    function load_Departments() {
        $.ajax({
                url: "get_department_list",
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
    load_Departments();

    $(document).on("click", '#edit-department-button', function(event) {
        let id = $(this).attr("data-id");
        get_datas(`departments/${id}`).then(res => {
            $('#editDepartmentModal #name').val(res.name);
            $('#editDepartmentModal').attr("data-id", id);
            makeOptionSelected('editDepartmentModal', 'activity', res.activity);
            $('#editDepartmentModal').modal('show');
        });
    });

    $('#addDepartmentForm').submit(function(e) {
        e.preventDefault();
        var values = {
            name: $("#addDepartmentForm #name").val(),
            activity: $("#addDepartmentForm #activity option:selected").val(), 
        };
        let store_url = $("#addDepartmentForm").attr('action');
        if ($("#addDepartmentForm").valid()) {
            $.ajax({
                url: store_url,
                type: "POST",
                data: values,
                dataType: 'json',
                success: function(data) { 
                    toastr.success(data.message)
                    $(`#addDepartmentModal`).modal('hide');
                    $(`#addDepartmentForm`).trigger("reset");
                    load_Departments();
                },
                error: function(err) {
                    console.dir(err)
                    toastr.error('Failed to save!')
                }
            });
        }
    });

    $('#editDepartmentForm').submit(function(e) {
        let id = $('#editDepartmentModal').attr("data-id");
        e.preventDefault();
        var values = {
            name: $("#editDepartmentForm #name").val(),
            activity: $("#editDepartmentForm #activity option:selected").val(), 
        };
        if ($("#editDepartmentForm").valid()) {
            $.ajax({
                url: "/departments/" + id,
                type: "PUT",
                data: values,
                dataType: 'json',
                success: function(data) {
                    toastr.success(data.message)
                    $(`#editDepartmentModal`).modal('hide');
                    $(`#editDepartmentForm`).trigger("reset");
                    load_Departments();
                },
                error: function(data) {
                    toastr.error('Failed to update!')
                }
            });
        }
    });
});
</script>
@endsection