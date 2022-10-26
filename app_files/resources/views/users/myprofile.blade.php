@extends('master')

@section('title')
My Profile
@endsection

@section('content') 
<div class="row">
    <div class="col-lg-4"></div>
    <div class="col-lg-4"> 
        <div id="changePasswordArea">
            <h3>Change Password</h3>
            <form method="post" id="changePasswordForm" enctype="multipart/form-data"> 
                <div class="form-group">
                    <label for="name">Current Password</label>
                    <input type="text" name="current_pass" id="current_pass" class="form-control" placeholder="Current Password">
                </div>
                <div class="form-group">
                    <label for="name">New Password</label>
                    <input type="text" name="new_pass_1" id="new_pass_1"  class="form-control" placeholder="New Password">
                </div>
                <div class="form-group">
                    <label for="name">New Password (Again)</label>
                    <input type="text" name="new_pass_2" id="new_pass_2"  class="form-control" placeholder="New Password (Again)">
                </div>
                <div class="buttons d-flex align-items-center justify-content-end"> 
                    <button type="submit" class="btn btn-primary btn-sm">Change Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('footer-scripts')
<script>
$(document).ready(function() {

    $("#changePasswordForm").validate({
        rules: {
            current_pass: {
                required: true, 
            },
            new_pass_1: {
                required: true, 
            },
            new_pass_2: {
                required: true,  
                equalTo: '#new_pass_1' 
            },
        },
        messages: {
            current_pass: {
                required: "Current Password is required", 
            },
            new_pass_1: {
                required: "New Password is required", 
            },
            new_pass_2: {
                required: "Repeat New password", 
                equalTo: "Not matched with new password",
            },
        }
    });
    
    $('#changePasswordForm').submit(function(e) {
        e.preventDefault(); 
        let current_pass = $("#changePasswordForm #current_pass").val()
        let new_pass_1 = $("#changePasswordForm #new_pass_1").val()
        let new_pass_2 = $("#changePasswordForm #new_pass_2").val()
        
        let values = {current_pass, new_pass_1, new_pass_2};
        
        if(current_pass && new_pass_1 && new_pass_2){
            $.ajax({
                url: '/update_my_password',
                type: "POST",
                data: values,
                dataType: 'json',
                success: function(data) {  
                    toastr.success(data.message) 
                    $(`#changePasswordForm`).trigger("reset"); 
                },
                error: function(err) { 
                    toastr.error(err.message)
                }
            });
        }
        
    });

});
</script>
@endsection