@extends('layout/master')
@section('title')
Kwikcaart | Deals
@endsection
@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<section class="content-main">
    <div class="row">
        <div class="col-12">
            <div class="content-header">
                <h2 class="content-title">Add New Deal</h2>

            </div>
        </div>
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h4>Deal information</h4>
                    <b>Please fill all the given information</b>
                </div>
                <div class="card-body">
                    <form id="brand_form">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="">
                                    <label class="form-label">Deal Title<span class="text-danger">*</span></label>
                                    <input placeholder="Enter deal title" type="text" name="title" id="title" class="form-control" required />
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <?php
                                $stores = App\Models\User::whereHas('roles', function ($q) {
                                    $q->where('name', 'Store');
                                })->get();
                                ?>
                                <label class="form-label">Select Store</label>
                                <div class="custom_select ">
                                    <select class="js-states form-control select2 select_store" name="store_id" id="input_category" required>
                                        <option value="" selected>Select Store</option>
                                        @foreach($stores as $store)
                                        <option value="{{$store->code}}">{{ucwords($store->name)}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">Start Date<span class="text-danger">*</span></label>
                                <input type="date" name="start_date" id="start_date" class="form-control" required />
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label">End Date<span class="text-danger">*</span></label>
                                <input type="date" name="end_date" id="end_date" class="form-control" required />
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label">discount Type</label>
                                <select name="discount_type" class="form-control">
                                    <option value="flat">Flat</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>
                            <div class="col-lg-6">
                                <label class="form-label">discount</label>
                                <input type="number" name="discount" id="discount" class="form-control" />
                            </div>
                            <!--<div class="col-lg-12">-->
                            <!--    <label class="form-label">Select Product</label>-->

                            <!--    <select name="products[]" id="products" class="form-control aiz-selectpicker select2  append_here" multiple required data-placeholder="Select Products" data-live-search="true" data-selected-text-format="count">-->
                            <!--        <option value="" disabled true>Select Product</option>-->
                            <!--    </select>-->
                            <!--</div>-->
                            <div class="col-lg-12">
                                <label class="form-label">Select Product</label>
                                <select class="form-control" name="products[]" id="products" multiple="multiple" data-ajax--url="{{ route('search-product') }}">
                                    
                                    <option value="">Select Product</option>
                                </select>

                            </div>
                        </div>

                        <div class="col-12 mb-4 text-end mt-4">
                            <button type="submit" class="btn btn-md rounded font-sm hover-up" id="btnSubmit"><i class="fas fa-spinner fa-spin" style="display: none;"></i> Save Deal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    input[type="date"]::-webkit-datetime-edit, input[type="date"]::-webkit-inner-spin-button, input[type="date"]::-webkit-clear-button {
  color: #fff;
  position: relative;
}

input[type="date"]::-webkit-datetime-edit-year-field{
  position: absolute !important;
  border-left:1px solid #8c8c8c;
  padding: 2px;
  color:#000;
  left: 56px;
}

input[type="date"]::-webkit-datetime-edit-month-field{
  position: absolute !important;
  border-left:1px solid #8c8c8c;
  padding: 2px;
  color:#000;
  left: 26px;
}


input[type="date"]::-webkit-datetime-edit-day-field{
  position: absolute !important;
  color:#000;
  padding: 2px;
  left: 4px;
  
}
</style>
<script>
   $(document).ready(function() {
    $('#products').select2({
        ajax: {
            url: $('#products').data('ajax--url'),
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    q: params.term, // User's search query
                    page: params.page || 1 // Current page number
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;

                return {
                    results: $.map(data, function (product) {
                        return {
                            id: product.id,
                            text: product.name
                        }
                    }),
                    pagination: {
                        more: false
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 3, // Minimum number of characters for search
    });
});
</script>
<script>
    $(document).ready(function(e) {
        $("#brand_form").on('submit', (function(e) {
            e.preventDefault();
            $.ajax({
                url: '/api/storedeals/add',
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
                        $("#brand_form")[0].reset();
                        window.location.href = "/store/deals";

                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }));

        // $(document).on('change', '.select_store', function() {

        //     var store_id = $('.select_store option:selected').val();

        //     $(".append_here").html('')
        //     $.ajax({
        //         url: "/get_products",
        //         type: "get",
        //         data: {
        //             store_id: store_id
        //         },
        //         dataType: "JSON",
        //         cache: false,
        //         success: function(response) {
        //             console.log(response);
        //             if (response["status"] == "fail") {} else if (response["status"] == "success") {
        //                 toastr.success('Success', response["msg"])
        //                 $('.append_here').html(response['html'])

        //             }
        //         },
        //         error: function(error) {
        //             console.log(error);
        //         }
        //     });
        // });


    });
</script>
@endsection