@extends('master')

@section('title')
Meeting Types
@endsection

@section('content')
<div class="card shadow mb-4 position-relative">
    <div class="ajax-loading"><img src="{{ asset('app_files/public/images/loader/loader1.gif') }}" /></div>
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">All Meeting Type</h6>
        <?php 
        $res = App\Http\Controllers\PermissionController::getNodePermissionByProfile('profiles');
        if($res['store'] == $res->route->id ){ 
        ?>
        <button data-toggle="modal" data-target="#addMeetingTypeModal" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add Meeting Type
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
                        <th width="20%" class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="results"></tbody>
            </table>
        </div>

        <!-- Meeting Type Add Modal -->
        <div class="modal" data-easein="shrinkIn" id="addMeetingTypeModal" tabindex="-1" role="dialog"
            aria-labelledby="aMeetingTypeModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="aMeetingTypeModal">Add Meeting Type</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('meeting_types.store') }}" method="post" id="addMeetingTypeForm">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="activity">Status</label>
                                    <select class="form-control" id="activity" name="activity">
                                      <option value="">Select Status</option>
                                      <option value="active">Active</option> 
                                      <option value="inactive">Inactive</option> 
                                    </select>
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

        <!-- Meeting Type Edit Modal -->
        <div class="modal" data-easein="shrinkIn" id="editaMeetingTypeModal" tabindex="-1" role="dialog"
            aria-labelledby="eMeetingTypeModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="eMeetingTypeModal">Edit Meeting Type</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="editMeetingTypeForm">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <label for="activity">Status</label>
                                    <select class="form-control" id="activity" name="activity">
                                      <option value="">Select Status</option>
                                      <option value="active">Active</option> 
                                      <option value="inactive">Inactive</option> 
                                    </select>
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
    $("#addMeetingTypeForm").validate({
        rules: {
            name: {
                required: true,
                maxlength: 50,
            },
        },
        messages: {
            name: {
                required: "Name is required",
                maxlength: "Name cannot be more than 50 characters"
            },
        }
    });

    function load_meeting_types() {
        $.ajax({
                url: "get_meeting_type_list",
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
    load_meeting_types();

    $(document).on("click", '#edit-meeting-type-button', function(event) {
        let id = $(this).attr("data-id");
        get_datas(`meeting_types/${id}`).then(res => { 
            $('#editaMeetingTypeModal').attr("data-id", id);
            $('#editaMeetingTypeModal #name').val(res.name);
            makeOptionSelected('editMeetingTypeForm', 'activity', res.activity);
            $('#editaMeetingTypeModal').modal('show');
        });
    });

    $('#addMeetingTypeForm').submit(function(e) {
        e.preventDefault();
        var values = {
            name: $("#addMeetingTypeForm #name").val(),
            activity: $("#addMeetingTypeForm #activity option:selected").val(), 

        };
        let store_url = $("#addMeetingTypeForm").attr('action');
        if ($("#addMeetingTypeForm").valid()) {
            $.ajax({
                url: store_url,
                type: "POST",
                data: values,
                dataType: 'json',
                success: function(data) {
                    toastr.success(data.message)
                    $(`#addMeetingTypeModal`).modal('hide');
                    $(`#addMeetingTypeForm`).trigger("reset");
                    load_meeting_types();
                },
                error: function(err) { 
                    toastr.error('Failed to save!')
                }
            });
        }
    });

    $('#editMeetingTypeForm').submit(function(e) {
        let id = $('#editaMeetingTypeModal').attr("data-id");
        e.preventDefault();
        var values = { 
            name: $("#editMeetingTypeForm #name").val(),
            activity: $("#editMeetingTypeForm #activity option:selected").val(), 
        };
        if ($("#editMeetingTypeForm").valid()) {
            $.ajax({
                url: "/meeting_types/" + id,
                type: "PUT",
                data: values,
                dataType: 'json',
                success: function(data) {
                    toastr.success(data.message)
                    $(`#editaMeetingTypeModal`).modal('hide');
                    $(`#editMeetingTypeForm`).trigger("reset");
                    load_meeting_types();
                },
                error: function(err) { 
                    toastr.error('Failed to update!')
                }
            });
        }
    });
});
</script>
@endsection