@extends('layout/master')
@section('title')
Safeer | Roles
@endsection
@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
    <span class="main-content-title mg-b-0 mg-b-lg-1">View Roles</span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Roles View</li>
        </ol>
    </div>
</div>
<!-- /breadcrumb -->
<!-- Row -->
<div class="row">
    <div class="col-lg-12">
        <div class="card custom-card overflow-hidden">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">List of added roles</h4>
                    <div class="tools">
                        <a href="#" class="btn btn-icon btn-primary" data-bs-target="#modaldemo1" data-bs-toggle="modal">
                            <i data-bs-toggle="tooltip" data-bs-title="Add New Role" class="fa fa-plus"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
            @if (Auth::user()->type == '1')
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                            $roles = Spatie\Permission\Models\Role::orderBy('id','DESC')->where('type',1)->get();
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                <thead>
                                    <tr>
                                        <th class="wd-20p">#ID</th>
                                        <th class="wd-25p">Role Name</th>
                                        <th class="wd-25p">Role Type</th>
                                        <th class="wd-25">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($roles) && sizeof($roles)>0)
                                        <?php $i=1; ?>
                                        @foreach($roles as $role)
                                        <?php 
                                                $typ = App\Models\UserType::where('id',$role->type)->first();
                                            ?>
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>{{$role->name}}</td>
                                                <td>
                                                    @if(! empty($typ))
                                                        {{$typ->name}}
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="javascript:;" id="{{$role->id}}" class="btn btn-warning button-icon btn-sm btnEdit" data-bs-target="#editModel" data-bs-toggle="modal">
                                                        <i class="fas fa-pencil tx-12"></i>
                                                    </a>
                                                    <!-- <a href="javascript:;" id="{{$role->id}}" class="btn btn-danger button-icon btn-sm btnDelete">
                                                        <i class="fas fa-trash tx-12"></i>
                                                    </a> -->
                                                    <a href="/role/permission/{{$role->id}}" id="{{$role->id}}" class="btn btn-info btn-sm button-icon">
                                                        <i class="fas fa-eye tx-12"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php $i++;?>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                         </div>
                    </div>
                </div>
                @elseif (Auth::user()->type=='2')
                <div class="row">
                    <div class="col-lg-12">
                        <?php
                            $roles = Spatie\Permission\Models\Role::orderBy('id','DESC')->where('store_id',auth()->user()->code)->where('type','2')->get();
                        ?>
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom" id="basic-datatable">
                                <thead>
                                    <tr>
                                        <th class="wd-20p">#ID</th>
                                        <th class="wd-25p">Role Name</th>
                                        <th class="wd-25p">Role Type</th>
                                        <th class="wd-25">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($roles) && sizeof($roles)>0)
                                        <?php $i=1; ?>
                                        @foreach($roles as $role)
                                        <?php 
                                                $typ = App\Models\UserType::where('id',$role->type)->first();
                                            ?>
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>{{$role->name}}</td>
                                                <td>
                                                    @if(! empty($typ))
                                                        {{$typ->name}}
                                                    @endif
                                                </td>

                                                <td>
                                                    <a href="javascript:;" id="{{$role->id}}" class="btn btn-warning button-icon btn-sm btnEdit" data-bs-target="#editModel" data-bs-toggle="modal">
                                                        <i class="fas fa-pencil tx-12"></i>
                                                    </a>
                                                    <!-- <a href="javascript:;" id="{{$role->id}}" class="btn btn-danger button-icon btn-sm btnDelete">
                                                        <i class="fas fa-trash tx-12"></i>
                                                    </a> -->
                                                    <a href="/role/permission/{{$role->id}}" id="{{$role->id}}" class="btn btn-info btn-sm button-icon">
                                                        <i class="fas fa-eye tx-12"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php $i++;?>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                         </div>
                    </div>
                </div>
            @endif
            </div>
        </div>
    </div>
</div>
<!-- End Row -->


<!-- Start modal -->
<div class="modal fade" id="modaldemo1">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Add Role</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="role_form">
          
                    <div class="form-group">
                        <input type="text" class="form-control" required id="" placeholder="Enter Role Name" name="name">
                    </div>
                    <?php
                       if (Auth::user()->type == '1'){
                        $types =App\Models\UserType::where('name','Admin')->get();
                    }elseif(Auth::user()->type == '2'){
                        $types =App\Models\UserType::where('name','Store')->get();
                    }else
                        $types =App\Models\UserType::orderBy('id','DESC')->get();
                    ?>
                    <div class="form-group">
                        <select name="type" class="form-control" required>
                            <option value="" selected> Select Type</option>
                            @foreach($types as $type)
                                <option value="{{$type->id}}">{{ucwords($type->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-end">
                    <button class="btn ripple btn-primary" id="btnSubmit" type="submit">
                        <i class="fas fa-spinner fa-spin" style="display: none"></i> Save Role
                    </button>
                    <button class="btn ripple btn-secondary" data-bs-dismiss="modal" type="button">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End modal -->


<!-- Start modal -->
<div class="modal fade" id="editModel">
    <div class="modal-dialog" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">Edit Role</h6><button aria-label="Close" class="btn-close" data-bs-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" id="edit_form">
                    <div class="form-group">
                        <input type="text" class="form-control" required id="editName" placeholder="Enter Role Name" name="name">
                        <input type="hidden" id="inputId" name="id">
                    </div>
                    <?php
                       if (Auth::user()->type == '1'){
                        $types =App\Models\UserType::where('name','Admin')->get();
                    }elseif(Auth::user()->type == '2'){
                        $types =App\Models\UserType::where('name','Store')->get();
                    }else
                        $types =App\Models\UserType::orderBy('id','DESC')->get();
                    ?>
                    <div class="form-group">
                        <select name="type" class="form-control" required>
                            <option value="" selected> Select Type</option>
                            @foreach($types as $type)
                                <option value="{{$type->id}}">{{ucwords($type->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-end">
                        <button class="btn ripple btn-primary btnSubmit" id="btnSubmit" type="submit">
                            <i class="fas fa-spinner fa-spin" style="display: none"></i> Update Role
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
        $("#role_form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/role/add',
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
                        $("#role_form")[0].reset();
                        location.reload();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }));
        $(document).on('click','.btnEdit',function (e) {
            e.preventDefault();
            var role = $(this).attr('id')
            $.ajax({
                url: '/api/role/'+role,
                type: "GET",
                dataType: "JSON",
                cache: false,
                beforeSend: function() {
                    $(".btnSubmit").attr('disabled', true);
                    $(".fa-spin").css('display', 'inline-block');
                },
                complete: function() {
                    $(".btnSubmit").attr('disabled', false);
                    $(".fa-spin").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        $("#editName").val(response["data"]["name"]);
                        $("#inputId").val(response["data"]["id"]);
                        if(response["type"] !="empty"){
                            $("#EditType").val(response["type"]["id"]);
                        }else{
                            $("#EditType").val('');

                        }

                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        })
        $(document).on('click', '.btnDelete', function(e) {
            var id = $(this).attr('id')
            Swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this Role!",
                type: "warning",
                buttons: true,
                confirmButtonColor: "#ff5e5e",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false,
                dangerMode: true,
                showCancelButton: true
            })
                .then((deleteThis) => {
                    if (deleteThis.isConfirmed) {
                        $.ajax({
                            url: '/api/role/delete/' + id,
                            type: "delete",
                            dataType: "JSON",
                            success: function(response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to delete Role.", "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Deleted!", "Role has been deleted.",
                                        "success");
                                    location.reload();
                                }
                            },
                            error: function(error) {
                                console.log(error);
                            },
                            async: false
                        });
                    } else {
                        Swal.close();
                    }
                });
        });
        $("#edit_form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/role/update',
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
                        $("#role_form")[0].reset();
                        location.reload();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }));
        
    </script>

@endsection
