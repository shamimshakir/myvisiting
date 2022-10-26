<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1"> 

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MyVisiting') }} | @yield('title')</title>

    <!-- Custom fonts for this template-->
    <link href="{{asset('vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{asset('css/sb-admin-2.min.css')}}" rel="stylesheet">
    <!-- Toaster css -->
    <link href="{{asset('css/toastr.css')}}" rel="stylesheet">

    <link href="{{asset('css/custom.css')}}" rel="stylesheet">
    
     <!-- Datatables CSS CDN -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css"> 
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap4.min.css"> 
    
     
    <!-- Jquery -->
    <script src="{{asset('vendor/jquery/jquery.min.js')}}"></script>
    <link rel="icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">

</head> 
<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
         
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center"
                href="{{ route('dashboard-home') }}">
                <!-- <div class="sidebar-brand-text mx-3">MyVisiting</div> -->
                <div class="sidebar-brand-text mx-3">
                    <img src="img/myvisiting.png" alt="">
                </div>
            </a>


            <div class="sidebar-user-box">
                <h2> {{ Auth::user()->name }} </h2>
                <h3> {{ Auth::user()->designation ? Auth::user()->designation->name : '' }} </h3>
                @if(Auth::user()->photo)
                <img class="rounded-circle" src="/app_files/public/images/users/{{Auth::user()->photo}}">
                @else
                <img class="rounded-circle" src="img/userdummy.jpg">
                @endif
            </div>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->route()->uri == '/' ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard-home') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- Heading -->
            <div class="sidebar-heading">
                Main Functionalities
            </div>

            <?php 
                use App\Models\Permission;
                $permissions = Permission::where('profile_id', Auth::user()->profile_id)->with('route')->get();  
                $data = ''; 
                if(count($permissions)){
                    foreach ($permissions as $permission) {
                        $act_class = "";
                        if(request()->route()->uri == $permission->route->route_name){
                            $act_class = 'active';
                        }
                        if($permission->route->id == $permission->view){
                            $data.= "<li class='nav-item ".$act_class."' data-id='".$permission->route->id."'>
                                <a class='nav-link' href=".$permission->route->route_name.">
                                    <i class='".$permission->route->icon."'></i>
                                    <span>".$permission->route->node_name."</span>
                                </a>
                            </li>"; 
                            if($permission->route->route_name == 'profiles'){
                                $data.= "<div class='sidebar-heading'>Advanced Setup</div>";
                            }
                        }
                    }
                }
                echo $data;
            ?>
            
            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">
            

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    
                    <div id="topbar_company_logo"></div>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    {{ Auth::user()->name }}
                                </span>
                                @if(Auth::user()->photo)
                                <img class="img-profile rounded-circle" src="/app_files/public/images/users/{{Auth::user()->photo}}">
                                @else
                                <img class="img-profile rounded-circle" src="img/userdummy.jpg">
                                @endif
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="{{ route('change_password') }}">
                                    <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Change Password
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    @yield('content')

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; <a href="https://nextech.com.bd/"> Nextech Limited </a> | 2022</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    
            <!-- Visitor Add Modal-->
        <div class="modal" id="addVisitorModal" tabindex="-1" role="dialog"
            aria-labelledby="visitorModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="visitorModal">Add Visitor</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('visitors.store') }}" method="post" id="addVisitorForm"
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
                                        <label for="email">Email</label>
                                        <input type="text" name="email" class="form-control" id="email"
                                            placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="visitor_type_id">Visitor Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="visitor_type_id" class="form-control"
                                            id="visitor_type_id">
                                            <option value="">Select Visitor Type</option> 
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
                                        <label for="visitor_company">Visitor Company</label>
                                        <input type="text" name="visitor_company" class="form-control" id="visitor_company"
                                            placeholder="Company">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="nid">NID</label>
                                        <input type="text" name="nid" class="form-control" id="nid" placeholder="NID">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="activity">Active Status <span class="text-danger">*</span></label>
                                        <select class="form-control" name="activity" class="form-control" id="activity">
                                            <option value="">Select Status</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="photo">Photo</label>
                                        <div class="custom-file">
                                            <input type="file" name="photo" accept="image/*" class="custom-file-input"
                                                id="photo" onchange="loadFile(event, 'addVisitorModal')">
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

        <!-- Visitor Edit Modal-->
        <div class="modal" id="editVisitorModal" tabindex="-1" role="dialog"
            aria-labelledby="visitorModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title m-0 font-weight-bold text-primary" id="visitorModal">Edit Visitor</h6>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="post" id="editVisitorForm" enctype="multipart/form-data">
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
                                        <label for="email">Email</label>
                                        <input type="text" name="email" class="form-control" id="email"
                                            placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="visitor_type_id">Visitor Type <span class="text-danger">*</span></label>
                                        <select class="form-control" name="visitor_type_id" class="form-control"
                                            id="visitor_type_id">
                                            <option value="">Select Visitor Type</option> 
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
                                        <label for="visitor_company">Visitor Company</label>
                                        <input type="text" name="visitor_company" class="form-control" id="visitor_company"
                                            placeholder="Company">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="nid">NID</label>
                                        <input type="text" name="nid" class="form-control" id="nid" placeholder="NID">
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="activity">Active Status <span class="text-danger">*</span></label>
                                        <select class="form-control" name="activity" class="form-control" id="activity">
                                            <option value="">Select Status</option>
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label for="photo">Photo</label>
                                        <div class="custom-file">
                                            <input type="file" name="photo" accept="image/*" class="custom-file-input"
                                                id="photo" onchange="loadFile(event, 'editVisitorModal')">
                                            <label class="custom-file-label" for="photo">Choose file</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>
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
        
        
        

    <script src="{{asset('vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

    <script src="{{asset('vendor/jquery-easing/jquery.easing.min.js')}}"></script>

    <script src="{{asset('js/sb-admin-2.min.js')}}"></script>
    <script src="{{asset('js/toastr.js')}}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.2.2/velocity.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/velocity/1.2.2/velocity.ui.min.js"></script>

        <!-- Datatables JS CDN -->

<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>

    <script>
    var loadFile = function(event, modal) {
        var output = document.querySelector('#' + modal + ' #output');
        output.src = URL.createObjectURL(event.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    };
    </script>
    
    <script src="{{asset('js/custom.js')}}"></script>

    @yield('footer-scripts')
    
    <script>
        $("#addVisitorForm").validate({
        rules: {
            name: {
                required: true,
                maxlength: 50,
            },
            email: {
                email: true,
            },
            phone: {
                required: true
            },
            visitor_type_id: {
                required: true
            },
            activity: {
                required: true
            },
        },
        messages: {
            name: {
                required: "Name is required",
                maxlength: "Name cannot be more than 50 characters"
            },
            phone: {
                required: "Phone is required"
            },
            visitor_type_id: {
                required: "Visitor Type is required"
            },
            activity: {
                required: "Active Status is required"
            },
        }
    });
    $("#editVisitorForm").validate({
        rules: {
            name: {
                required: true,
                maxlength: 50,
            },
            email: {
                email: true,
            },
            phone: {
                required: true
            },
            visitor_type_id: {
                required: true
            },
            activity: {
                required: true
            },
        },
        messages: {
            name: {
                required: "Name is required",
                maxlength: "Name cannot be more than 50 characters"
            },
            phone: {
                required: "Phone is required"
            },
            visitor_type_id: {
                required: "Visitor Type is required"
            },
            activity: {
                required: "Active Status is required"
            },
        }
    });

    $(document).on("click", '#edit-visitor-button', function(event) {
        let id = $(this).attr("data-id");
        get_datas(`visitors/${id}`).then(res => {
            $('#editVisitorModal').attr("data-id", id);
            $('#editVisitorForm #name').val(res.name);
            $('#editVisitorForm #email').val(res.email);
            $('#editVisitorForm #phone').val(res.phone);
            $('#editVisitorForm #visitor_company').val(res.visitor_company);
            $('#editVisitorForm #nid').val(res.nid);
            makeOptionSelected('editVisitorForm', 'activity', res.activity);
            $('#editVisitorModal #previousImg').attr('src', 'app_files/public/images/visitors/' + res.photo);
            $('#editVisitorForm #address').val(res.address);
            
            $('#editVisitorForm #visitor_type_id').empty();
            
            let visitor_types_p = get_datas('/get_visitor_types');
            
            Promise.all([visitor_types_p]).then((values) => {
                $('#editVisitorForm #visitor_type_id').append(
                    `<option value="">Select Visitor Type</option>`);
                values[0].forEach(el => {
                    let option = `<option value="${el.id}">${el.name}</option>`;
                    $('#editVisitorForm #visitor_type_id').append(option);
                });
                makeOptionSelected('editVisitorForm', 'visitor_type_id', res.visitor_type_id); 

                $('#editVisitorModal').modal('show');
            });
            
        });
    });


    $("#add-visitor-button").click(function() {
        $('#addVisitorForm #visitor_type_id').empty();
        let visitor_types_p = get_datas('/get_visitor_types');
        Promise.all([visitor_types_p]).then((values) => {
            $('#addVisitorForm #visitor_type_id').append(
                `<option value="">Select Visitor Type</option>`);
            values[0].forEach(el => {
                let option = `<option value="${el.id}">${el.name}</option>`;
                $('#addVisitorForm #visitor_type_id').append(option);
            });
            $('#addVisitorModal').modal('show');
        });
    });
    
    (function (){
        get_datas('/company/view').then(res => {
            let img = `<img src="/app_files/public/images/company/${res.logo}" />`;
            $('#topbar_company_logo').html(img);
        });
    })(); 

    $('#addVisitorForm').submit(function(e) {
        e.preventDefault();
        let store_url = $("#addVisitorForm").attr('action');
        let formData = new FormData(this);
        if ($("#addVisitorForm").valid()) {
            $.ajax({
                url: store_url,
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    console.dir(data)
                    toastr.success(data.message)
                    $('#addVisitorModal').modal('hide');
                    $('#addVisitorForm').trigger("reset");
                    load_visitors();
                },
                error: function(err) {
                    console.dir(err)
                    toastr.error('Failed to save!')
                }
            })
        }
    });
    $('#editVisitorForm').submit(function(e) {
        e.preventDefault();
        let id = $('#editVisitorModal').attr("data-id");
        let formData = new FormData(this);
        if ($("#editVisitorForm").valid()) {
            $.ajax({
                url: "/visitors/" + id,
                method: "post",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    toastr.success(data.message)
                    $('#editVisitorModal').modal('hide');
                    $('#editVisitorModal').trigger("reset");
                    load_visitors();
                },
                error: function(err) {
                    console.dir(err)
                    toastr.error('Failed to update!')
                }
            })
        }
    });
    </script>

</body>

</html>