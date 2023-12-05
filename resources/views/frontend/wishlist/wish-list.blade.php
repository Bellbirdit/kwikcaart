@extends('user/layout/master')
@section('title')
Kwikcaart | wishlist
@endsection
@section('content')

<main class="content-main">
    <!-- <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">


            </div>
        </div>
    </div> -->
    <div class="container">
        <div class="row">
            <div class="col-xl-10 col-lg-12 m-auto">
                <div class="mb-50">
                    <h1 class="heading-2 mb-10">Your Wishlist</h1>
                    <h6 class="text-body">There are <span class="text-brand wishlist_count">{{ $wishlist_count }}</span>
                        products in this list</h6>
                </div>
                <div class="table-responsive shopping-summery">
                    <table class="table table-wishlist">
                        <thead>
                            <tr class="main-heading">
                                <th class="custome-checkbox start pl-30">
                                    <!-- <label class="form-check-label" for="exampleCheckbox11"></label> -->
                                </th>
                                <th scope="col" colspan="2">Product</th>
                                <th scope="col">Price</th>
                              
                                <th scope="col">Action</th>
                                <th scope="col" class="end">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wishlists as $wishlist)
                                <tr class="pt-30">
                                    <td class="custome-checkbox pl-30">
                                        <!-- <label class="form-check-label" for="exampleCheckbox1"></label> -->
                                    </td>
                                    <td class="image product-thumbnail pt-40">
                                    <?php 
                                        $pro = App\Models\Product::where('id',$wishlist->product_id)->first();
                                       
                                    ?>
                                        <img src="{{ asset('uploads/files/'.$pro->getImage($pro->thumbnail)) }}" alt="#" />
                                    </td>
                                    <td class="product-des product-name">
                                        <h6><a class="product-name mb-10"
                                                href="shop-product-right.html">{{ App\Models\Product::where(['id'=>$wishlist->product_id])->pluck('name')->first() }}</a>
                                        </h6>
                                        <div class="product-rate-cover">
                                            <div class="product-rate d-inline-block">
                                                <div class="product-rating" style="width: 90%"></div>
                                            </div>
                                            <span class="font-small ml-5 text-muted"> (4.0)</span>
                                        </div>
                                    </td>
                                    <td class="price" data-title="Price">
                                        <h3 class="text-brand">
                                            AED {{ App\Models\Product::where(['id'=>$wishlist->product_id])->pluck('discounted_price')->first() }}
                                        </h3>
                                    </td>
                                  
                                    <td class="text-right" data-title="Cart">
                                        <a href="javascript:;" class="btn btn-sm add_cart" id="{{$wishlist->product_id}}" > <i class="fa fa-plus"></i>  Add to cart</a>
                                    </td>
                                    <td class="action text-center" data-title="Remove">
                                        <a href="javascript:;" class="btnDelete" id="{{ $wishlist->id }}"
                                            class="text-body"><i class="fi-rs-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on('click', '.btnDelete', function (e) {
        var id = $(this).attr('id')
        Swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this Product!",
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
                        url: '/api/delete/wishlist/' + id,
                        type: "delete",
                        dataType: "JSON",
                        success: function (response) {

                            if (response["status"] == "fail") {
                                Swal.fire("Failed!", "Failed to delete Product.", "error");
                            } else if (response["status"] == "success") {
                                Swal.fire("Deleted!", "Product has been deleted.",
                                    "success");
                                location.reload();
                            }
                        },
                        error: function (error) {
                            console.log(error);
                        },
                        async: false
                    });
                } else {
                    Swal.close();
                }
            });
    });

    $(document).on('click', '.add_cart', function () {
            $(".append_cart").html('')
            var id = $(this).attr('id');
            $.ajax({
                url: "/add/cart/" + id,
                type: "get",
                dataType: "JSON",
                cache: false,
                success: function (response) {
                    console.log(response);
                    if (response["status"] == "fail") {} else if (response["status"] == "success") {
                        $(".append_cart").html(response['html'])
                        toastr.success('Success', response["msg"])
                        $(".total").html(response['total'])
                        $('.cart_count').html(response['cart_count'])

                    }
                },
                error: function (error) {
                    console.log(error);
                }
            });
        });

</script>
@endsection
