@extends('master')

@section('title')
Front Desk
@endsection

@section('content')
<div class="card shadow mb-4 position-relative"> 
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Front Desk</h6>
    </div>
    <div class="card-body">
        <div class="search-form">
            <form method="post" id="searchFrontDeskMeetingForm">
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
                            <label for="meeting_status">Meeting Status</label>
                            <select class="form-control" name="meeting_status" class="form-control" id="meeting_status">
                                <option value="">Select Status</option>
                                <option value="new">New</option>
                                <option value="approved" selected>Approved</option>
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
                            <input type="date" name="from_date" class="form-control" id="from_date" value="<?php echo date('Y-m-d'); ?>"
                                placeholder="Meeting Date" >
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="to_date">To Date</label>
                            <input type="date" name="to_date" class="form-control" id="to_date" value="<?php echo date('Y-m-d'); ?>"
                                placeholder="Meeting Date">
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="meeting_id">Meeting ID</label>
                            <input type="text" name="meeting_id" class="form-control"
                                id="meeting_id" placeholder="Meeting ID">
                        </div>
                    </div> 
                    <div class="col-lg-2">
                        <button type="submit" class="btn btn-primary btn-sm" style="margin-top: 27px;">Search</button>
                    </div>
                </div>
            </form>
        </div> 
        <div class="table-responsive mt-4">
            <table id="meetingTableFront" class="table table-bordered table-striped table-sm d-none" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>SL</th> 
                        <th>Meeting ID</th>
                        <th>Visitor Name</th>
                        <th>Visiting Date</th>
                        <th>Visiting Time</th>
                        <th>Type</th> 
                        <th>Status</th> 
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="results"></tbody>
            </table>
        </div>
        
        
        <!-- Authorize Visitor Modal -->
        <div class="modal" data-easein="flipXIn" id="authorizeVisitorModal" tabindex="-1" role="dialog"
            aria-labelledby="meetingModal" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="meetingModal">Authorize The Visitor</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="authoriseVisForm"> 
                            <div class="form-group">
                                <label for="meeting_code">Meeting Code (Write meeting code to check )</label>
                                <input type="text" name="meeting_code" class="form-control"
                                    id="meeting_code" placeholder="Meeting Code">
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
    $("#authoriseVisForm").validate({
        rules: {
            meeting_code: {
                required: true,
                minlength: 10,
            } 
        },
        messages: {
            meeting_code: {
                required: "Meeting Code is required",
                minlength: "Meeting Code cannot be less than 10 characters"
            } 
        }
    });
    

    // Date.prototype.toDateInputValue = (function() {
    //     var local = new Date(this);
    //     local.setMinutes(this.getMinutes() - this.getTimezoneOffset());
    //     return local.toJSON().slice(0,10);
    // });
    // $('#from_date').val(new Date().toDateInputValue());
    // $('#to_date').val(new Date().toDateInputValue());
    
    function load_search_box_visitors(){
        $('#searchFrontDeskMeetingForm #visitor_id').empty();
        let visitors_p = get_datas('/get_visitors');
        Promise.all([visitors_p]).then((values) => {
            $('#searchFrontDeskMeetingForm #visitor_id').append(
                `<option value="">Select Visitor</option>`);
            values[0].forEach(el => {
                let option = `<option value="${el.id}">${el.name}</option>`;
                $('#searchFrontDeskMeetingForm #visitor_id').append(option);
            }); 
        });
    }
    load_search_box_visitors();
    
    $(document).on("click", '#checkin-button', function(event) {
        let id = $(this).attr("data-id");
        $('#authorizeVisitorModal').attr("data-id", id);
        $('#authorizeVisitorModal').modal('show');
    });
    
    $('#authoriseVisForm').submit(function(e) {
        e.preventDefault();
        var values = { 
            meeting_code: $("#authoriseVisForm #meeting_code").val(),
            id: $('#authorizeVisitorModal').attr("data-id"),
        };
        if ($("#authoriseVisForm").valid()) {
            $.ajax({
                url: "/authorize_to_checkin",
                type: "post",
                data: values,
                dataType: 'json',
                success: function(data) {
                    console.dir(data)
                    toastr.success(data.message)
                    $(`#authorizeVisitorModal`).modal('hide');
                    $(`#authoriseVisForm`).trigger("reset");
                    $("#searchFrontDeskMeetingForm").submit();
                },
                error: function(err) { 
                    console.dir(err)
                    toastr.error("Code MisMatched😔")
                }
            });
        }
    });
    
    
    $(document).on("click", '#checkout-button', function(event) {
        let id = $(this).attr("data-id");
        var values = {
            id: id,
            status: "checkout",
        }; 
        $.ajax({
            url: '/meetings/change_status',
            type: "POST",
            data: values,
            dataType: 'json',
            success: function(data) {
                console.dir(data)
                toastr.success(data.message) 
                $("#searchFrontDeskMeetingForm").submit();
            }, 
            error: function(err) {
                console.dir(err)
                toastr.error('Failed to save!')
            }
        });
        
        
    });
    
    
    $('#searchFrontDeskMeetingForm').submit(function(e) {
        e.preventDefault();
        $("#meetingTableFront").removeClass("d-none");
        let visitor_id = $("#searchFrontDeskMeetingForm #visitor_id").val();
        let from_date = $("#searchFrontDeskMeetingForm #from_date").val(); 
        let to_date = $("#searchFrontDeskMeetingForm #to_date").val();
        let meeting_status = $("#searchFrontDeskMeetingForm #meeting_status").val();    
        let meeting_id = $("#searchFrontDeskMeetingForm #meeting_id").val();  
        let table = $('#meetingTableFront').DataTable({
            processing: true,
            serverSide: true, 
            ordering: false,
            lengthChange: false,
            searching: false,  
            bDestroy: true,  
            "ajax": {
                "url": "{{route('get_front_desk_meetings')}}", 
                "type": "post",
                "method": "post",
                "data": {
                    "visitor_id": visitor_id,
                    "from_date": from_date, 
                    "to_date": to_date,
                    "meeting_status": meeting_status, 
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
                { data: 'meeting_id' },
                { data: 'visitor_name' },
                { data: 'meeting_date' },
                { data: 'meeting_time' },
                { data: 'type_name' }, 
                { data: 'meeting_status' }, 
                { data: 'action', className: 'text-center' },
            ]
        }); 
    });


});
</script>
@endsection