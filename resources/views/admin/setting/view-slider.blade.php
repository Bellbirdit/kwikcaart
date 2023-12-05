@extends('layout/master')
@section('title')
Kwikcaart | Home Slider
@endsection
@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Home Slider</h2>

        </div>
        <div>
            <a href="{{ url('add/slider') }}" class="btn btn-primary"><i
                    class="text-muted material-icons md-post_add"></i>Add New Slider</a>
        </div>
    </div>
    <div class="card mb-4">

        <div class="card-body">
            <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-6 row-cols-xl-6" id="divData">

            </div>

        </div>
    </div>

    <div id="divLoader" class="text-center pt-5" style="display:block;height:300px;">
        <span>
            <i class="fas fa-spinner fa-spin"></i>
            {{ __('Slider is being loading.. It might take few seconds') }}.
        </span>
    </div>
    <div class="row text-center" id="divNotFound" style="display:none">
        <h6 class="mt-lg-5" style=""><i class="bx bx-window-close"></i>
            {{ __('No items are found in Slider') }} !</h6>
    </div>
</section>
<script type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    $(document).ready(function (e) {
        var contentNotFound = $("#divNotFound");
        var contentFound = $("#divData");
        list()
        function list() {
            $("#divLoader").css('display', 'block')
            $("#divData").css('display', 'none')
            $("#divNotFound").css('display', 'none')
            $("#divData").html('');
            $.ajax({
                url: '/api/slider/list',
                type: "get",

                dataType: "JSON",
                cache: false,
                beforeSend: function () {

                },
                complete: function () {

                },
                success: function (response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        $("#divLoader").css('display', 'none')
                        $("#divData").css('display', 'none')
                        $("#divNotFound").css('display', 'block')
                    } else if (response["status"] == "success") {
                        $("#divNotFound").css('display', 'none')
                        $("#divLoader").css('display', 'none')
                        $("#divData").css('display', 'flex')
                        $("#divData").append(response["rows"])

                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
    });
</script>
<script>
      // delete
      $(document).on('click', '.btnDelete', function (e) {
            var id = $(this).attr('id')
            Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this item!",
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
                            url: '/api/slider/delete/' + id,
                            type: "delete",
                            dataType: "JSON",
                            success: function (response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to delete item.",
                                        "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Deleted!", "Item has been deleted.",
                                        "success");
                                    Count()
                                    
                                }
                            },
                            error: function (error) {
                                // console.log(error);
                            },
                            async: false
                        });
                    } else {
                        Swal.close();
                    }
                });
        });
</script>


@endsection
