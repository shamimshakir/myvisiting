@extends('master')

@section('title')
Visitors
@endsection

@section('content')
<div class="card shadow mb-4 position-relative" id="visitors">
    <div class="ajax-loading"><img src="{{ asset('app_files/public/images/loader/loader2.gif') }}" /></div>
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Visitors</h6>
        <?php 
        $res = App\Http\Controllers\PermissionController::getNodePermissionByProfile('visitors');
        if($res['store'] == $res->route->id ){ 
        ?>
        <button id="add-visitor-button" class="btn btn-sm btn-primary">
            <i class="fas fa-plus"></i> Add Visitor
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
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody id="results"></tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@section('footer-scripts')
<script>
    function load_visitors() {
        $.ajax({
                url: "get_visitor_list",
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
    load_visitors();
</script>
@endsection