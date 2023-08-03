@extends('layout/master')

@section('title')
Brand Edit
@endsection

@section('content')
<section class="content-main">
    <div class="row">
        <div class="col-12">
            <div class="content-header">
                <h2 class="content-title">Edit Brand</h2>
                <!-- <div>
                    <button class="btn btn-light rounded font-sm mr-5 text-body hover-up">Save to draft</button>
                    <button class="btn btn-md rounded font-sm hover-up">Publich</button>
                </div> -->
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Update brand information</h4>
                    <b>Please fill all the given information</b>
                </div>
                <div class="card-body">
                    <form id="brand_form">
                    <input type="hidden" name="id" value="{{$brand->id}}"/>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="">
                                    <label class="form-label">Brand Name<span class="text-danger">*</span></label>
                                    <input placeholder="Enter brand name here" value="{{$brand->name}}" type="text" name="name" id="name" class="form-control" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Logo<span class="text-danger">*</span></label>
                                <input  type="file" name="logo" id="logo" class="form-control"  />
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label">Order_level</label>
                                <input value="{{$brand->order_level}}" type="number" name="order_level" id="order_level" class="form-control" />
                            </div>
                                <div class="mb-2">
                                   <label for="product_name" class="form-label">Brand Code</label>
                                   <input type="number" placeholder="Type here" class="form-control" id="brand_code" name="brand_code" />
                               </div>
                            <div class="col-lg-12">
                                <label class="form-label">Meta title</label>
                                <input type="text" name="meta_title"  value="{{$brand->meta_title}}" id="meta_title" class="form-control" />
                            </div>
                            <div class="col-lg-12">
                                <label class="form-label">Meta description</label>
                                <textarea name="meta_description" id="meta_description" cols="30" rows="2" class="form-control" >{{$brand->meta_description}}</textarea>
                            </div>
                        </div>

                        <div class="col-12 mb-4 text-end mt-4">
                            <button type="submit" class="btn btn-md rounded font-sm hover-up" id="btnSubmit"><i class="fas fa-spinner fa-spin" style="display: none;"></i> Save Brand</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(document).ready(function(e) {
        

        $("#brand_form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/brand/update',
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
                        window.location.href = "/brand/list";
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }));
    });
</script>
@endsection 