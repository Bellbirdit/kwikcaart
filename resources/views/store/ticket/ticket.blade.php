@extends('layout/master')
@section('title')
Amor Rozgar | Tickets
@endsection
@section('content')

<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
    <span class="main-content-title mg-b-0 mg-b-lg-1">Support Ticket</span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ticket</li>
        </ol>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card mt-1 mb-4">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">List of added Tickets</h4>
                    <div class="tools">
                        <a href="/store/add/ticket" class="btn btn-icon btn-primary"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-12 tex-center" id="divLoader">
                        <p>Loading...</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div id="divData" style="display:none">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Ticket ID</th>
                                            <th>User</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<!-- start for ticket add delete -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
$(document).ready(function(e) {

    //get ticket
    var table = $('#dataTable').DataTable();
    // var pageInfo=table.page.info()
    function datatable(rows) {
        $("#dataTable tbody").empty();
        $("#dataTable").DataTable().clear();
        $("#dataTable").DataTable().destroy();
        $("#tBody tr").remove();
        $("#dataTable tbody").empty();
        $("#tBody").append(rows);
        table = $("#dataTable").DataTable({order:[[0,"desc"]]});
        // table.page(pageInfo.page).draw(false)
    }
    tickets();

    function tickets() {
        datatable('');
        $("#divLoader").css('display', 'block')
        $("#divData").css('display', 'none')
        $.ajax({
            url: '/api/ticket/view',
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            type: "get",
            dataType: "JSON",
            cache: false,
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(response) {
                console.log(response);
                if (response["status"] == "fail") {
                    $("#divLoader").css('display', 'none')
                    $("#divData").css('display', 'block')
                } else if (response["status"] == "success") {
                    $("#divLoader").css('display', 'none')
                    $("#divData").css('display', 'block')
                    datatable(response["rows"]);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }


    //delete ticket
    $(document).on('click', '.btnDeleteTickets', function(e) {
        var id = $(this).attr('id');
        Swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this Ticket!",
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
                        url: '/api/ticket/delete/' + id,
                        type: "get",
                        dataType: "JSON",
                        success: function(response) {

                            if (response["status"] == "fail") {
                                Swal.fire("Failed!", "Failed to delete Ticket.",
                                    "error");
                            } else if (response["status"] == "success") {
                                Swal.fire("Deleted!", "Ticket has been deleted.",
                                    "success");
                                tickets();

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


})
</script>
<!-- end  -->
@endsection