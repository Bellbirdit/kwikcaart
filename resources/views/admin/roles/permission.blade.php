@extends('layout/master')
@section('title')
Kwikcaart | Permission
@endsection
@section('content')
<style>
.heading {
    font-weight: 600;
    letter-spacing: 1px;
}
</style>
<!-- breadcrumb -->
<div class="container-fluid">
    <div class="breadcrumb-header justify-content-between">
        <div class="left-content">
            <span class="main-content-title mg-b-0 mg-b-lg-1">Permission</span>
        </div>
        <div class="justify-content-center mt-2">
            <ol class="breadcrumb">
                <li class="breadcrumb-item tx-15"><a href="/admin/dashboard">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Permission</li>
            </ol>
        </div>
    </div>
    <!-- /breadcrumb -->
    <!-- Row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card custom-card overflow-hidden">
                <div class="card-header pb-0" bis_skin_checked="1">
                    <div class="d-flex justify-content-between" bis_skin_checked="1">
                        <h4 class="card-title mg-b-0">List of added roles</h4>
                        <div class="tools" bis_skin_checked="1">
                            <a href="#" class="btn ripple btn-primary" data-bs-target="#modaldemo1"
                                data-bs-toggle="modal"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <form id="assign_form">
                        @csrf
                        <div class="row" id="panelsRow">
                        @if (Auth::user()->hasRole('Admin'))
                        <div class="col-lg-12 mb-4">
                              <div class="panel panel-primary">
                                <div class="panel-heading p-1 pt-2" style="display: flex; justify-content: space-between; align-items: center;">
                                  <label class="heading">User Management</label>
                                </div>
                                <div class="panel-body" style="display: flex; flex-wrap: wrap;">
                                  <div class="row flex-column" id="user_section">
                                    @if(isset($user_permission) && sizeof($user_permission)>0)
                                      @foreach($user_permission as $up)
                                         <label class="p-2 mt-2 d-flex" style="flex-basis: 50%;">
                                          <span class="check-box mb-0 me-3">
                                            <span class="ckbox">
                                              <input type="checkbox" name="permission[]" class="userCheck" id="permission" value="{{$up->id}}" {{ $role->hasPermissionTo($up) ? 'checked' : '' }}>
                                              <span></span>
                                            </span>
                                          </span>
                                          <span class="my-auto">{{$up->name}}</span>
                                        </label>
                                      @endforeach
                                    @endif
                                  </div>
                                </div>
                              </div>
                            </div>

                          
                        <div class="col-lg-12 mb-4">
                              <div class="panel panel-primary">
                                <div class="panel-heading p-1 pt-2" style="display: flex; justify-content: space-between; align-items: center;">
                                  <label class="heading">Product Management</label>
                                </div>
                                <div class="panel-body" style="display: flex; flex-wrap: wrap;">
                                  @if(isset($product_permission) && sizeof($product_permission)>0)
                                  @foreach($product_permission as $pp)
                                  <label class="p-2 mt-2 d-flex" style="flex-basis: 50%;">
                                    <span class="check-box mb-0 ms-2">
                                      <span class="ckbox">
                                        <input type="checkbox" name="permission[]" class="userCheck" id="permission" value="{{$pp->id}}" {{ $role->hasPermissionTo($pp) ? 'checked' : '' }}>
                                        <span></span>
                                      </span>
                                    </span>
                                    <span class="mx-3 my-auto">{{$pp->name}}</span>
                                  </label>
                                  @endforeach
                                  @endif
                                </div>
                              </div>
                            </div>

                          
                        <div class="col-lg-12 mb-4">
                                <div class="panel panel-primary">
                                    <div class="panel-heading p-1 pt-2" style="display: flex; justify-content: space-between; align-items: center;">
                                        <label class="heading">Order Management</label>
                                    </div>
                                    <div class="panel-body" style="display: flex; flex-wrap: wrap;">
                                        @if(isset($order_permission) && sizeof($order_permission)>0)
                                        @foreach($order_permission as $op)
                                         <label class="p-2 mt-2 d-flex" style="flex-basis: 50%;">
                                            <span class="check-box mb-0 me-2">
                                                <span class="ckbox">
                                                    <input type="checkbox" name="permission[]" class="userCheck"
                                                        id="permission" value="{{$op->id}}"
                                                        {{ $role->hasPermissionTo($op) ? 'checked' : '' }}>
                                                    <span></span>
                                                </span>
                                            </span>
                                            <span>{{$op->name}}</span>
                                        </label>
                                        @endforeach
                                        @endif
                                    </div>
     </div>
                            </div>


                        <div class="col-lg-12 mb-4">
  <div class="panel panel-primary">
    <div class="panel-heading p-1 pt-2" style="display: flex; justify-content: space-between; align-items: center;">
      <label class="heading">General Setting</label>
    </div>
      <div class="panel-body" style="display: flex; flex-wrap: wrap;">
      <div class="row" id="product_section">
        @if(isset($general_setting) && sizeof($general_setting)>0)
          @foreach($general_setting as $op)
             <label class="p-2 mt-2 d-flex" style="flex-basis: 50%;">
              <span class="check-box mb-0 ms-2">
                <span class="ckbox">
                  <input type="checkbox" name="permission[]" class="userCheck" id="permission" value="{{$op->id}}" {{ $role->hasPermissionTo($op) ? 'checked' : '' }}>
                  <span></span>
                </span>
              </span>
              <span class="mx-3 my-auto">{{$op->name}}</span>
            </label>
          @endforeach
        @endif
      </div>
    </div>
  </div>
</div>

                           
                            @elseif (Auth::user()->hasRole('Store'))

                            <div class="col-lg-12 mb-4 ">
                                <div class="panel panel-primary">
                                    <div class="panel-heading p-1 pt-2" style="">
                                        <label class="heading">Store Staff Setting</label>
                                    </div>
                                    <div class="panel-body ">
                                        <div class="row" id="product_section">
                                            @if(isset($storestaff_setting) && sizeof($storestaff_setting)>0)
                                            @foreach($storestaff_setting as $op)
                                            <label class="p-2 mt-2">
                                                <span class="check-box mb-0 ms-2">
                                                    <span class="ckbox">
                                                        <input type="checkbox" name="permission[]" class="userCheck"
                                                            id="permission" value="{{$op->id}}"
                                                            {{ $role->hasPermissionTo($op) ? 'checked' : '' }}>
                                                        <span></span>
                                                    </span>
                                                </span>
                                                <span class="mx-3 my-auto">{{$op->name}}</span>
                                            </label>
                                            @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif


                        </div>
                        <input type="hidden" name="role" value="{{$role->id}}">
                        <button class="btn btn-primary mt-4 float-end btnPermission" type="submit">
                            <i class="fas fa-spinner fa-spin" style="display: none"></i> ASSIGN PERMISSION
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Row -->
</div>

<!-- Start modal -->
<div class="modal fade" id="modaldemo1">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Add Permission</h6><button aria-label="Close" class="btn-close"
                    data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="permission_form">
                    <div class="form-group">
                        <label>NAME</label>
                        <input type="text" class="form-control" required id="" placeholder="Enter permission"
                            name="name">
                    </div>
                    <div class="form-group">
                        <label>SECTION</label>
                        <select class="form-control" name="section" required>
                        @if (Auth::user()->hasRole('Admin'))
                            <option value="" selected>--select permission--</option>
                            <option value="user_management">User Management</option>
                            <option value="product_management">Product Management</option>
                            <option value="Order_management">Order Management</option>
                            <option value="general_setting">General Setting</option>
                            @elseif (Auth::user()->hasRole('Store'))
                            <option value="storestaff_setting">Store Staff Setting</option>
                            @endif
                        </select>
                    </div>
                    <div class="text-end">
                        <button class="btn ripple btn-primary" id="btnSubmit" type="submit">
                            <i class="fas fa-spinner fa-spin" style="display: none"></i> Save Permission
                        </button>
                        <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                    </div>
                </form>
            </div>



        </div>
    </div>
</div>
<!-- End modal -->
<script>
$(document).on('submit', '#permission_form', function(e) {
    e.preventDefault();
    $.ajax({
        url: '/api/permission/add',
        type: "POST",
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function() {
            $("#btnSubmit").attr('disabled', true);
            $(".fa-spin").css('display', 'inline-block');
        },
        complete: function() {
            $("#btnSubmit").attr('disabled', false);
            $(".fa-spin").css('display', 'none');
        },
        success: function(response) {
            console.log(response);
            if (response["status"] == "fail") {
                toastr.error('Failed', response["msg"])
            } else if (response["status"] == "success") {
                toastr.success('Success', response["msg"])
                $("#permission_form")[0].reset();
                location.reload();
            }
        },
        error: function(error) {
            console.log(error);
        }
    });
})
$(document).on('submit', '#assign_form', function(e) {
    e.preventDefault();
    $.ajax({
        url: '/api/permission/assign',
        type: "POST",
        data: new FormData(this),
        dataType: "JSON",
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function() {
            $(".btnPermission").attr('disabled', true);
            $(".fa-spin").css('display', 'inline-block');
        },
        complete: function() {
            $(".btnPermission").attr('disabled', false);
            $(".fa-spin").css('display', 'none');
        },
        success: function(response) {
            console.log(response);
            if (response["status"] == "fail") {
                toastr.error('Failed', response["msg"])
            } else if (response["status"] == "success") {
                toastr.success('Success', response["msg"])


            }
        },
        error: function(error) {
            console.log(error);
        }
    });
})
</script>
@endsection