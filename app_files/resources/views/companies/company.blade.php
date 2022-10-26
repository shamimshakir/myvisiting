@extends('master')

@section('title')
Company Setup
@endsection

@section('content')
<div class="card shadow mb-4 position-relative">
    <div class="card-header d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">Company Setup</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('company_update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              {{ $message }}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
        @endif
            
            
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" class="form-control" id="company_name" name="company_name" value="{{ $company->company_name }}">
                 </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="company_name">Company Address</label>
                    <input type="text" class="form-control" id="comapny_address" name="comapny_address" value="{{ $company->comapny_address }}">
                 </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="company_name">Phone</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ $company->phone }}">
                 </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ $company->email }}" readonly>
                 </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="web_address">Web Address</label>
                    <input type="text" class="form-control" id="web_address" name="web_address" value="{{ $company->web_address }}">
                 </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="v_code">V code</label>
                    <input type="text" class="form-control" id="v_code" name="v_code" value="{{ $company->v_code }}">
                 </div>
            </div>
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="sode_sl">V code</label>
                    <input type="text" class="form-control" id="sode_sl" name="sode_sl" value="{{ $company->sode_sl }}">
                 </div>
            </div> 
            <div class="col-lg-4">
                <label>Logo</label>
                <div class="input-group">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="logo" id="logoInputImg" accept="image/*">
                        <label class="custom-file-label" for="logoInputImg">Choose file</label>
                    </div>
                </div>
                
                <label>Current Logo</label>
                <div class="current_logo_show_box">
                    <img src="/app_files/public/images/company/{{ $company->logo }}" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <button type="submit" class="btn btn-sm btn-primary">Update Information</button>
            </div>
        </div>
        </form>
    </div>
</div>
@endsection

