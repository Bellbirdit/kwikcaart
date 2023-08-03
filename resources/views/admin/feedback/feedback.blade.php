@extends('layout/master')

@section('title')
Safeer | Order Feedback
@endsection

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">


@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Order Feedback</h2>
        </div>
    </div>
    <!-- main section -->
    <div class="row">
        <div class="card mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover pt-4 mb-4" id="myTable">
                        <thead>
                            <tr>
                                <th>Feedback from</th>
                                <th>Order Number</th>
                                <th>Heading</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody id="divData">
                        </tbody>
                    </table>
                </div>
                <div id="divLoader" class="text-center pt-5" style="display:block;height:300px;">
                    <span>
                        <i class="fas fa-spinner fa-spin"></i>
                        {{ __('Data is being loading.. It might take few seconds') }}.
                    </span>
                </div>
                <div class="row text-center" id="divNotFound" style="display:none">
                    <h6 class="mt-lg-5" style=""><i class="bx bx-window-close"></i>
                        {{ __('No Record Found') }} !
                    </h6>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')

<script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        function Storelist() {
            $("#divLoader").css('display', 'block')
            $("#divData").css('display', 'none')
            $("#divNotFound").css('display', 'none')
            $("#divData").html('');
            $.ajax({
                url: '/api/view/order/feedback',
                type: "get",
                data: {},
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
                        $("#divData").css('display', 'contents')
                        $("#divData").append(response["rows"])
                        $('#myTable').DataTable();
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }
        Storelist();
    });
</script>
@endsection
