@extends('layout/master')
@section('title')
Safeer | Edit Page
@endsection
@section('content')

<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">Edit Page</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" id="page_form">
                    <input type="hidden" name="id" value="{{ $pagedata->id }}" />
                    <div class="form-group row mb-2">

                        <label class="col-md-2 col-form-label">Title<span class="text-danger">*</span></label>
                        <div class="col-md-10">
                            <input type="text" placeholder="Title" id="title" name="title" class="form-control"
                                value="{{ $pagedata->title }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-from-label" for="name">Link <span
                                class="text-danger">*</span></label>
                        <div class="col-md-10 ">
                            <div class="input-group d-block d-md-flex">
                                <div class=" input-group-prepend  d-md-flex">
                                    <span
                                        class="input-group-text form-control flex-grow-1">{{ url('/') }}/
                                    </span>
                                    <span class="w-100 w-md-auto" placeholder="Slug">
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-md-2 col-form-label"> Add Content<span class="text-danger">*</span>
                        </label>
                        <div class="col-md-10">
                            <textarea name="content" id="" class="summernote">{{ $pagedata->content }}</textarea>
                        </div>
                    </div>

                    <div class="form-group mb-0 text-end">
                        <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fas fa-spinner fa-spin"
                                style="display: none;"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/vendors/jquery-3.6.0.min.js') }}"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.summernote').summernote({
            height: 300,
        });
    });

</script>
<script>
    $(document).ready(function (e) {

        $("#page_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/page/update',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-spin").css('display', 'inline-block');
                },
                complete: function () {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-spin").css('display', 'none');
                },
                success: function (response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        window.location.href = "/all/pages";
                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }));
    });

</script>

@endsection
