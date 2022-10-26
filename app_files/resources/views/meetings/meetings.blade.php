@extends('master')

@section('title')
Meetings
@endsection

@section('content')
<div class="card shadow mb-4 position-relative"> 
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">All Meeting</h6>
        <?php
        $res = App\Http\Controllers\PermissionController::getNodePermissionByProfile('meetings');
        if($res['store'] == $res->route->id ){ 
        ?>
        <button id="add-meeting-button" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add Meeting
        </button>
        <?php } ?>
        
    </div>
    <div class="card-body">
        <div class="search-form">
            <form method="post" id="searchMeetingForm">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="visitor_id">Visitor</label>
                            <select class="form-control" name="visitor_id" class="form-control" id="visitor_id">
                                <option value="">Select Visitor</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="meeting_type_id">Meeting Type</label>
                            <select class="form-control" name="meeting_type_id" class="form-control" id="meeting_type_id">
                                <option value="">Select Type</option>
                            </select>
                        </div>
                    </div>  
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="meeting_status">Meeting Status</label>
                            <select class="form-control" name="meeting_status" class="form-control" id="meeting_status">
                                <option value="">Select Status</option>
                                <option value="new">New</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="expired">Expired</option>
                                <option value="checkin">Checkin</option>
                                <option value="checkout">Checkout</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="from_date">From Date</label>
                            <input type="date" name="from_date" class="form-control" id="from_date"
                                placeholder="Meeting Date">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="to_date">To Date</label>
                            <input type="date" name="to_date" class="form-control" id="to_date"
                                placeholder="Meeting Date">
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="meeting_id">Meeting ID</label>
                            <input type="text" name="meeting_id" class="form-control"
                                id="meeting_id" placeholder="Meeting ID">
                        </div>
                    </div> 
                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-info btn-sm" style="margin-top: 27px;">Search</button>
                    </div>
                </div>
            </form>
        </div> 
        <div class="table-responsive mt-4">
            <table id="meetingTable" class="table table-bordered table-striped table-sm" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>SL</th>
                        <th class="no-sort">Apply Date</th>
                        <th class="no-sort">Meeting ID</th>
                        <th class="no-sort">Visitor Name</th>
                        <th>Visiting Date</th>
                        <th class="no-sort">Visiting Time</th>
                        <th class="no-sort">Type</th>
                        <th class="no-sort">Reason</th> 
                        <th class="no-sort">Status</th>
                        <th class="no-sort">Code</th>
                        <th class="no-sort">Action</th>
                    </tr>
                </thead>
                <tbody id="results"></tbody>
            </table>
        </div>
        <!-- Meeting Add Modal -->
        <div class="modal" data-easein="flipYIn" id="addMeetingModal" tabindex="-1" role="dialog"
            aria-labelledby="meetingModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="meetingModal">Add Meeting</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('meetings.store') }}" method="post" id="addMeetingForm">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="visitor_id">Visitor <span class="text-danger">*</span>
                                            <?php 
                                            $res = App\Http\Controllers\PermissionController::getNodePermissionByProfile('visitors');
                                            if($res['store'] == $res->route->id ){ 
                                            ?>
                                            <button type="button" id="add-visitor-button" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                            <?php } ?>
                                        </label>
                                        <select class="form-control" name="visitor_id" class="form-control"
                                            id="visitor_id">
                                            <option value="">Select Visitor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="meeting_date">Meeting Date <span class="text-danger">*</span></label>
                                        <input type="date" name="meeting_date" class="form-control" id="meeting_date"
                                            placeholder="Meeting Date">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="meeting_time">Meeting Time <span class="text-danger">*</span></label>
                                        <input type="time" name="meeting_time" class="form-control" id="meeting_time"
                                            placeholder="Meeting Time">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="meeting_type_id">Meeting Type</label>
                                        <select class="form-control" name="meeting_type_id" class="form-control"
                                            id="meeting_type_id">
                                            <option value="">Select Type</option>
                                            <option value="personal">Personal</option>
                                            <option value="official">Official</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="meeting_reason">Meeting Reason</label>
                                        <input type="text" name="meeting_reason" class="form-control"
                                            id="meeting_reason" placeholder="Meeting Reason">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="meeting_code">Meeting Code <span class="text-danger">*</span></label>
                                        <input type="text" name="meeting_code" class="form-control" id="meeting_code"
                                            placeholder="Meeting Code">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <button id="code_generator" type="button" style="margin-top: 27px;"
                                        class="btn btn-success btn-sm">Generate
                                        Code</button>
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

        <!-- Meeting Edit Modal -->
        <div class="modal" data-easein="flipYIn" id="editmeetingModal" tabindex="-1" role="dialog"
            aria-labelledby="meetingModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="meetingModal">Edit Meeting</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="editMeetingForm">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="visitor_id">Visitor <span class="text-danger">*</span></label>
                                        <select class="form-control" name="visitor_id" class="form-control"
                                            id="visitor_id">
                                            <option value="">Select Visitor</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="meeting_date">Meeting Date <span class="text-danger">*</span></label>
                                        <input type="date" name="meeting_date" class="form-control" id="meeting_date"
                                            placeholder="Meeting Date">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="meeting_time">Meeting Time <span class="text-danger">*</span></label>
                                        <input type="time" name="meeting_time" class="form-control" id="meeting_time"
                                            placeholder="Meeting Time">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="meeting_type_id">Meeting Type</label>
                                        <select class="form-control" name="meeting_type_id" class="form-control"
                                            id="meeting_type_id">
                                            <option value="">Select Type</option>
                                            <option value="personal">Personal</option>
                                            <option value="official">Official</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="meeting_reason">Meeting Reason</label>
                                        <input type="text" name="meeting_reason" class="form-control"
                                            id="meeting_reason" placeholder="Meeting Reason">
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

function load_visitors(){
    $('#addMeetingForm #visitor_id').empty();
    $('#addMeetingForm #meeting_type_id').empty();

    let visitors_p = get_datas('/get_visitors');
    let meeting_types_p = get_datas('/get_meeting_types');
    
    Promise.all([visitors_p, meeting_types_p]).then((values) => {
        $('#addMeetingForm #visitor_id').append(
            `<option value="">Select Visitor</option>`);
        values[0].forEach(el => {
            let option = `<option value="${el.id}">${el.name}</option>`;
            $('#addMeetingForm #visitor_id').append(option);
        });
        $('#addMeetingForm #meeting_type_id').append(
            `<option value="">Select Meeting Type</option>`);
        values[1].forEach(el => {
            let option = `<option value="${el.id}">${el.name}</option>`;
            $('#addMeetingForm #meeting_type_id').append(option);
        });
    });
}

    
$(document).ready(function() {
    $("#addMeetingForm").validate({
        rules: {
            meeting_code: {
                required: true,
                minlength: 10,
            },
            meeting_date: {
                required: true
            },
            meeting_time: {
                required: true
            },
            visitor_id: {
                required: true
            },
        },
        messages: {
            meeting_code: {
                required: "Meeting Code is required",
                minlength: "Meeting Code cannot be less than 10 characters"
            },
            meeting_date: {
                required: "Meeting Date is required",
            },
            meeting_time: {
                required: "Meeting Time is required",
            },
            visitor_id: {
                required: "Visitor is required",
            },
        }
    });

    $("#editMeetingForm").validate({
        rules: {
            meeting_date: {
                required: true
            },
            meeting_time: {
                required: true
            },
            visitor_id: {
                required: true
            },
        },
        messages: {
            meeting_date: {
                required: "Meeting Date is required",
            },
            meeting_time: {
                required: "Meeting Time is required",
            },
            visitor_id: {
                required: "Visitor is required",
            },
        }
    });

    function load_search_box_visitors(){
        $('#searchMeetingForm #visitor_id').empty();
        let visitors_p = get_datas('/get_visitors');
        let meeting_types_p = get_datas('/get_meeting_types');
        $('#searchMeetingForm #meeting_type_id').empty();
        Promise.all([visitors_p, meeting_types_p]).then((values) => {
            $('#searchMeetingForm #visitor_id').append(
                `<option value="">Select Visitor</option>`);
            values[0].forEach(el => {
                let option = `<option value="${el.id}">${el.name}</option>`;
                $('#searchMeetingForm #visitor_id').append(option);
            });
            $('#searchMeetingForm #meeting_type_id').append(
            `<option value="">Select Type</option>`);
            values[1].forEach(el => {
                let option = `<option value="${el.id}">${el.name}</option>`;
                $('#searchMeetingForm #meeting_type_id').append(option);
            });
        });
    }
    load_search_box_visitors();

    function load_meetings(visitor_id = '', meeting_status = '', meeting_type_id = '', meeting_id = '', from_date = '', to_date = '') {
        let table = $('#meetingTable').DataTable({
            processing: true,
            serverSide: true, 
            searching: false,  
            bDestroy: true,
            ordering: true,
            columnDefs: [{
                orderable: false,
                targets: "no-sort"
            }],
            dom: 'Bfrtip',
            buttons: [ 
                {
                    extend: 'pageLength',
                    className: 'btn btn-primary btn-sm'
                }, 
                {
                    extend: 'copy',
                    className: 'btn btn-info btn-sm'
                }, 
                {
                    extend: 'excel',
                    className: 'btn btn-warning btn-sm'
                }, 
                {
                    extend: 'csv',
                    className: 'btn btn-info btn-sm'
                }, 
                {
                    extend: 'pdf',
                    className: 'btn btn-warning btn-sm'
                }, 
                {
                    extend: 'print',
                    className: 'btn btn-sm btn-primary'
                }, 
                {
                    extend: 'colvis',
                    className: 'btn btn-success btn-sm'
                },  
            ],
            "ajax": {
                "url": "{{route('get_meeting_list')}}", 
                "type": "post",
                "data": {
                    "visitor_id": visitor_id,
                    "from_date": from_date, 
                    "to_date": to_date,
                    "meeting_status": meeting_status,
                    "meeting_type_id": meeting_type_id,
                    "meeting_id": meeting_id,
                },
                "complete": function(res){
                    console.log(res);   
                },
                error: function(err) {
                    console.dir(err);
                }
            },
            columns: [
                { data: 'id' },
                { data: 'apply_date' },
                { data: 'meeting_id' },
                { data: 'visitor_name' },
                { data: 'meeting_date' },
                { data: 'meeting_time' },
                { data: 'type_name' },
                { data: 'meeting_reason' }, 
                { data: 'meeting_status' },
                { data: 'meeting_code' },
                { data: 'action' },
            ]
        });
        table.buttons().container().appendTo( $('#meetingTable_wrapper .col-md-6:eq(0)', table.table().container() ) );
    }
    load_meetings();

    $('#searchMeetingForm').submit(function(e) {
        e.preventDefault();
        let visitor_id = $("#searchMeetingForm #visitor_id").val();
        let from_date = $("#searchMeetingForm #from_date").val(); 
        let to_date = $("#searchMeetingForm #to_date").val();
        let meeting_status = $("#searchMeetingForm #meeting_status").val();  
        let meeting_type_id = $("#searchMeetingForm #meeting_type_id").val();  
        let meeting_id = $("#searchMeetingForm #meeting_id").val();  
        $('#meetingTable').DataTable().destroy();
        load_meetings(visitor_id, meeting_status, meeting_type_id, meeting_id, from_date, to_date);
    });

    $(document).on("click", '#edit-meeting-button', function(event) {
        let id = $(this).attr("data-id");
        get_datas(`meetings/${id}`).then(res => {
            $('#editmeetingModal').attr("data-id", id);
            $('#editmeetingModal #meeting_date').val(res.meeting_date);
            $('#editmeetingModal #meeting_time').val(res.meeting_time); 
            $('#editmeetingModal #visitor_id').val(res.visitor_id);
            $('#editmeetingModal #meeting_reason').val(res.meeting_reason);
            $('#editMeetingForm #visitor_id').empty();
            $('#editMeetingForm #meeting_type_id').empty();
            
            let visitors_p = get_datas('/get_visitors');
            let meeting_types_p = get_datas('/get_meeting_types');
            
            Promise.all([visitors_p, meeting_types_p]).then((values) => {
                $('#editMeetingForm #visitor_id').append(
                    `<option value="">Select Visitor</option>`);
                values[0].forEach(el => {
                    let option = `<option value="${el.id}">${el.name}</option>`;
                    $('#editMeetingForm #visitor_id').append(option);
                });
                $('#editMeetingForm #meeting_type_id').append(
                    `<option value="">Select Meeting Type</option>`);
                values[1].forEach(el => {
                    let option = `<option value="${el.id}">${el.name}</option>`;
                    $('#editMeetingForm #meeting_type_id').append(option);
                });
                
                makeOptionSelected('editMeetingForm', 'visitor_id', res.visitor_id);
                makeOptionSelected('editMeetingForm', 'meeting_type_id', res.meeting_type_id);
                $('#editmeetingModal').modal('show');
            });
        });
    });

    $(document).on("change", '#addMeetingForm #meeting_date', function(event) {
        var UserDate = event.target.value;
        var ToDate = new Date();
        if (new Date(UserDate).getDate() < ToDate.getDate()) {
            toastr.error("The Date must be Bigger or Equal to today date")
            $("#addMeetingForm #meeting_date").val('');
            return false;
        }
        return true;
    });

    $("#add-meeting-button").click(function() {
        load_visitors();
        $('#addMeetingModal').modal('show');
    });

    $("#code_generator").click(function() {
        let code = generate_code(10);
        $('#addMeetingForm #meeting_code').val(code);
    });

    $('#addMeetingForm').submit(function(e) {
        e.preventDefault();
        var values = {
            visitor_id: $("#addMeetingForm #visitor_id").val(),
            meeting_date: $("#addMeetingForm #meeting_date").val(),
            meeting_time: $("#addMeetingForm #meeting_time").val(),
            meeting_reason: $("#addMeetingForm #meeting_reason").val(),
            meeting_code: $("#addMeetingForm #meeting_code").val(), 
            meeting_type_id: $("#addMeetingForm #meeting_type_id").val()
        };
        let store_url = $("#addMeetingForm").attr('action');
        if ($("#addMeetingForm").valid()) {
            $.ajax({
                url: store_url,
                type: "POST",
                data: values,
                dataType: 'json',
                success: function(data) {
                    toastr.success(data.message)
                    $(`#addMeetingModal`).modal('hide');
                    $(`#addMeetingForm`).trigger("reset");
                    load_meetings();
                },
                error: function(err) {
                    console.dir(err)
                    toastr.error('Failed to save!')
                }
            });
        }
    });

    $('#editMeetingForm').submit(function(e) {
        let id = $('#editmeetingModal').attr("data-id");
        e.preventDefault();
        var values = {
            visitor_id: $("#editMeetingForm #visitor_id").val(),
            meeting_date: $("#editMeetingForm #meeting_date").val(),
            meeting_time: $("#editMeetingForm #meeting_time").val(),
            meeting_type_id: $("#editMeetingForm #meeting_type_id").val(), 
            meeting_reason: $("#editMeetingForm #meeting_reason").val(),
        };
        if ($("#editMeetingForm").valid()) {
            $.ajax({
                url: "/meetings/" + id,
                type: "PUT",
                data: values,
                dataType: 'json',
                success: function(data) {
                    console.dir(data)
                    toastr.success(data.message)
                    $(`#editmeetingModal`).modal('hide');
                    $(`#editMeetingForm`).trigger("reset");
                    load_meetings();
                },
                error: function(err) {
                    console.dir(err)
                    toastr.error('Failed to update!')
                }
            });
        }
    });

    function changeStatus(id, status) {
        var values = {
            id: id,
            status: status,
        };
        $.ajax({
            url: '/meetings/change_status',
            type: "POST",
            data: values,
            dataType: 'json',
            success: function(data) {
                toastr.success(data.message)
                $(`#addMeetingModal`).modal('hide');
                $(`#addMeetingForm`).trigger("reset");
                load_meetings();
            },
            error: function(err) {
                toastr.error('Failed to save!')
            }
        });
    }

    $(document).on("click", '#send-code-button', function(event) {
        let client_id = "evisit";
        let api_key = "9b17ed02799f01bd";
        let secret_key = "ae49d611";
        
        let id = $(this).attr("data-id");
        get_datas(`meetings/${id}`).then(res => {
            let phone = res.visitor.phone;
            let visitor_name = res.visitor.name;
            let user_name = res.user.name;
            let meeting_time = res.meeting_time;
            let meeting_date = res.meeting_date;
            let meeting_code = res.meeting_code;
            
            let message_body = `Dear ${visitor_name}, Your meeting with Mr. ${user_name} from ${meeting_time} on ${meeting_date}, meeting code: ${meeting_code}`;
            
            let submit_url = `http://smpp.ajuratech.com:7788/sendtext?apikey=${api_key}&secretkey=${secret_key}&callerID=${client_id}&toUser=${phone}&messageContent=${message_body}`;
            
            $.ajax({
                url: submit_url,
                type: "GET",
                dataType: 'jsonp',
                success: function(data) {
                    console.dir(data)
                    toastr.success("SMS sent successfully");  
                },
                error: function(err) {
                    console.dir(err)
                    toastr.error('Failed to send sms!')
                }
            });
        });
    });

    $(document).on("click", '#reject-meeting-button', function(event) {
        let id = $(this).attr("data-id");
        changeStatus(id, 'rejected');
    });

    $(document).on("click", '#approve-meeting-button', function(event) {
        let id = $(this).attr("data-id");
        changeStatus(id, 'approved');
    });
});
</script>
@endsection