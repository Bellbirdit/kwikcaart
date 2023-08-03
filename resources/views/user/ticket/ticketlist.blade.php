@extends('frontend/layout/master')
@section('title')
Safeer | Shop
@endsection
@section('frontend/content')
<link href="{{ asset('assets/css/main.css?v=1.1') }}" rel="stylesheet" type="text/css" />

<section class="content-main">
<div class="row" id="corner">
    <div class="col-lg-12">
        <div class="card1">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                    <h4 class="card-title mg-b-0">List of added Tickets</h4>
                    <div class="tools d-flex flex-row">
                        <a href="/user/ticket/list" class="btn btn-icon btn-sm btn-primary me-2" title="Back To Tickets"><i class="fa fa-arrow-left"></i></a>
                        <a href="/user/ticket" class="btn btn-icon btn-sm btn-primary" title="Generate ticket"><i class="fa fa-plus"></i></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-5"></div>
                    <div class="col-lg-4" id="divLoader">
                        <p>Loading...</p>
                    </div>
                    <div class="col-lg-3"></div>
                </div>
                <div class="col-12">
                    <div id="divData" style="display:none">
                        <div class="">

                            <table class="table table-sm table-bordered" id="" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Ticket ID</th>
                                        <th>User</th>
                                        <th>Subject</th>
                                        <th>Status</th>
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
</section>

@endsection

@section('scripts')
<!-- Internal Data tables -->

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
        table = $("#dataTable").DataTable();
        // table.page(pageInfo.page).draw(false)
    }
    tickets();

    function tickets() {
        datatable('');
        $("#divLoader").css('display', 'block')
        $("#divData").css('display', 'none')
        $.ajax({
            url: '/api/ticket/list',
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
    $(document).on('click', '.btnDeleteTicket', function(e) {
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




