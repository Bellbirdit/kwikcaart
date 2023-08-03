<div class="tab-pane fade" id="wishlist" role="tabpanel" aria-labelledby="wishlist-tab">
    <main class="content-main">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 m-auto">
                    <div class="mb-50">
                       
                        <h6 class="text-body">There are <span
                                class="text-brand wishlist_count">{{ $wishlist_count }}</span>
                            products in this list</h6>
                    </div>
                    <div class="table-responsive shopping-summery">
                        <table class="table table-wishlist bg-white">
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
                                            @php
                                                $pro = App\Models\Product::where('id',$wishlist->product_id)->first();
                                                $img = $pro->getImage($pro->thumbnail);
                                            @endphp
                                            <img src="{{ asset('uploads/files/'.$img) }}"
                                                alt="#" />
                                        </td>
                                        <td class="product-des product-name">
                                            <h6><a class="product-name mb-10"
                                                    href="#">{{ App\Models\Product::where(['id'=>$wishlist->product_id])->pluck('name')->first() }}</a>
                                            </h6>
                                            <!--<div class="product-rate-cover">-->
                                            <!--    <div class="product-rate d-inline-block">-->
                                            <!--        <div class="product-rating" style="width: 90%"></div>-->
                                            <!--    </div>-->
                                            <!--    <span class="font-small ml-5 text-muted"> (4.0)</span>-->
                                            <!--</div>-->
                                        </td>
                                        <td class="price" data-title="Price">
                                            <h6 class="text-brand">
                                                AED
                                                {{ App\Models\Product::where(['id'=>$wishlist->product_id])->pluck('discounted_price')->first() }}
                                            </h6>
                                        </td>

                                        <td class="text-right" data-title="Cart">
                                            <a href="javascript:;" class="btn btn-sm add_cart"
                                                id="{{ $wishlist->product_id }}"> 
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                                                      <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
                                                    </svg>
                                                </a>
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
</div>

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

   
</script>

