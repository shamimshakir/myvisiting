@extends('master')

@section('title')
Designations
@endsection

@section('content')
<div class="card shadow mb-4 position-relative">
    <div class="ajax-loading"><img src="{{ asset('app_files/public/images/loader/loader1.gif') }}" /></div>
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">All Designations</h6>
        <?php 
        $res = App\Http\Controllers\PermissionController::getNodePermissionByProfile('designations');
        if($res['store'] == $res->route->id ){ 
        ?>
        <button data-toggle="modal" data-target="#addDesignationModal" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add Designation
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
        <!-- Designation Add Modal -->
        <div class="modal" data-easein="pulse" id="addDesignationModal" tabindex="-1" role="dialog"
            aria-labelledby="designationModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="designationModal">Add Designation
                        </h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('designations.store') }}" method="post" id="addDesignationForm">
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

        <!-- Designation Edit Modal -->
        <div class="modal" data-easein="pulse" id="editDesignationModal" tabindex="-1" role="dialog"
            aria-labelledby="designationModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="designationModal">Edit Designation
                        </h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="editDesignationForm">
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
    $("#addDesignationForm").validate({
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

    function load_Designations() {
        $.ajax({
                url: "get_designation_list",
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
    load_Designations();


    $(document).on("click", '#edit-designation-button', function(event) {
        let id = $(this).attr("data-id");
        get_datas(`designations/${id}`).then(res => {
            $('#editDesignationModal #name').val(res.name);
            $('#editDesignationModal').attr("data-id", id);
            makeOptionSelected('editDesignationModal', 'activity', res.activity);
            $('#editDesignationModal').modal('show');
        });
    });

    $('#addDesignationForm').submit(function(e) {
        e.preventDefault();
        var values = {
            name: $("#addDesignationForm #name").val(),
            activity: $("#addDesignationForm #activity option:selected").val(), 
        };
        let store_url = $("#addDesignationForm").attr('action');
        if ($("#addDesignationForm").valid()) {
            $.ajax({
                url: store_url,
                type: "POST",
                data: values,
                dataType: 'json',
                success: function(data) {
                    toastr.success(data.message)
                    $(`#addDesignationModal`).modal('hide');
                    $(`#addDesignationForm`).trigger("reset");
                    load_Designations();
                },
                error: function(data) {
                    toastr.error('Failed to save!')
                }
            });
        }
    });

    $('#editDesignationForm').submit(function(e) {
        let id = $('#editDesignationModal').attr("data-id");
        e.preventDefault();
        var values = {
            name: $("#editDesignationForm #name").val(),
            activity: $("#editDesignationForm #activity option:selected").val(), 
        };
        if ($("#editDesignationForm").valid()) {
            $.ajax({
                url: "/designations/" + id,
                type: "PUT",
                data: values,
                dataType: 'json',
                success: function(data) {
                    toastr.success(data.message)
                    $(`#editDesignationModal`).modal('hide');
                    $(`#editDesignationForm`).trigger("reset");
                    load_Designations();
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