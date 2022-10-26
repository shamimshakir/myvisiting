@extends('master')

@section('title')
Visitor Types
@endsection

@section('content')
<div class="card shadow mb-4 position-relative">
    <div class="ajax-loading"><img src="{{ asset('app_files/public/images/loader/loader1.gif') }}" /></div>
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">All Visitor Type</h6>
        <?php 
        $res = App\Http\Controllers\PermissionController::getNodePermissionByProfile('profiles');
        if($res['store'] == $res->route->id ){ 
        ?>
        <button data-toggle="modal" data-target="#addVisitorTypeModal" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add Visitor Type
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

        <!-- Visitor Type Add Modal -->
        <div class="modal" data-easein="shrinkIn" id="addVisitorTypeModal" tabindex="-1" role="dialog"
            aria-labelledby="aVisitorTypeModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="aVisitorTypeModal">Add Visitor Type</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('visitor_types.store') }}" method="post" id="addVisitorTypeForm">
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

        <!-- Visitor Type Edit Modal -->
        <div class="modal" data-easein="shrinkIn" id="editaVisitorTypeModal" tabindex="-1" role="dialog"
            aria-labelledby="eVisitorTypeModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="eVisitorTypeModal">Edit Visitor Type</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="editVisitorTypeForm">
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
    $("#addVisitorTypeForm").validate({
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

    function load_visitor_types() {
        $.ajax({
                url: "get_visitor_type_list",
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
    load_visitor_types();

    $(document).on("click", '#edit-visitor-type-button', function(event) {
        let id = $(this).attr("data-id");
        get_datas(`visitor_types/${id}`).then(res => { 
            $('#editaVisitorTypeModal').attr("data-id", id);
            $('#editaVisitorTypeModal #name').val(res.name);
            makeOptionSelected('editVisitorTypeForm', 'activity', res.activity);
            $('#editaVisitorTypeModal').modal('show');
        });
    });

    $('#addVisitorTypeForm').submit(function(e) {
        e.preventDefault();
        var values = {
            name: $("#addVisitorTypeForm #name").val(),
            activity: $("#addVisitorTypeForm #activity option:selected").val(), 

        };
        let store_url = $("#addVisitorTypeForm").attr('action');
        if ($("#addVisitorTypeForm").valid()) {
            $.ajax({
                url: store_url,
                type: "POST",
                data: values,
                dataType: 'json',
                success: function(data) {
                    toastr.success(data.message)
                    $(`#addVisitorTypeModal`).modal('hide');
                    $(`#addVisitorTypeForm`).trigger("reset");
                    load_visitor_types();
                },
                error: function(err) { 
                    toastr.error('Failed to save!')
                }
            });
        }
    });

    $('#editVisitorTypeForm').submit(function(e) {
        let id = $('#editaVisitorTypeModal').attr("data-id");
        e.preventDefault();
        var values = { 
            name: $("#editVisitorTypeForm #name").val(),
            activity: $("#editVisitorTypeForm #activity option:selected").val(), 
        };
        if ($("#editVisitorTypeForm").valid()) {
            $.ajax({
                url: "/visitor_types/" + id,
                type: "PUT",
                data: values,
                dataType: 'json',
                success: function(data) {
                    toastr.success(data.message)
                    $(`#editaVisitorTypeModal`).modal('hide');
                    $(`#editVisitorTypeForm`).trigger("reset");
                    load_visitor_types();
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