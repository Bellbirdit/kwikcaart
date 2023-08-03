@extends('layout/master')
@section('title')
Safeer | Pages
@endsection
@section('content')
<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Pages List</h2>
           
        </div>
         <div>
            <a href="{{ url('add/new-page') }}" class="btn btn-primary"><i
                    class="text-muted material-icons md-post_add"></i>Add New Page</a>
        </div>
    </div>
    <div class="card mb-4">
      
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th>#ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">URL</th>
                            <th scope="col" class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><b>{{ $page->title }}</b></td>
                                <td><a href="{{ route('custom-pages.show_custom_page', $page->slug) }}">{{ route('custom-pages.show_custom_page', $page->slug) }}</a></td>
                                <td>
                                <a href="{{ url('page/edit/'.$page->id) }}" data-toggle="tooltip" title="edit" class="btn btn-info button-icon btn-sm" data-placement="bottom">
                                        <i class="fas fa-pencil tx-12"></i>
                                    </a>

                                    <a href="javascript:;" id="{{ $page->id }}" title="delete" class="btnDelete btn btn-danger button-icon btn-sm" data-placement="bottom">
                                        <i class="fas fa-trash tx-12"></i>
                                    </a>
                                </td>
                        
                               
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- table-responsive //end -->
        </div>
        <!-- card-body end// -->
    </div>

</section>
@endsection
@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/vendors/jquery-3.6.0.min.js') }}"></script>
<script src="//cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
        $(document).on('click', '.btnDelete', function (e) {
            var id = $(this).attr('id')
            Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this page!",
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
                            url: '/api/delete/page/' + id,
                            type: "delete",
                            dataType: "JSON",
                            success: function (response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to delete page.",
                                        "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Deleted!", "Page has been deleted.",
                                        "success");
                                        location.reload();
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
    });
</script>
<script>

</script>
@endsection
