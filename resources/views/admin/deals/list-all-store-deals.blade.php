@extends('layout/master')
@section('title')
Safeer | All Store Deals
@endsection
@section('content')



<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">All Store Deals</h2>
        </div>
    </div>
    <div class="card mb-4">
        <div class="container-fluid">
            <div class="content-header">
                <div>
                    <h2 class="content-title card-title">Deals List</h2>
                </div>
                <div>
                    <a href="{{url('create/deals')}}" class="btn btn-primary"><i class="text-muted material-icons md-post_add"></i>Add New Deal</a>
                </div>
            </div>
            @foreach($storewise as $swdeals)
            <div class="card mb-4">
                <div class="card-heading">
                    <div class="row d-flex">
                        <div class="col-lg-12">
                            <h4>{{$swdeals->title}}</h4>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-3 ">
                            <label for="">Start Date</label>
                            <h5> {{$swdeals->start_date}}</h5>
                        </div>
                        <div class="col-lg-3 ">
                            <label for="">End Date</label>
                            <h5> {{$swdeals->end_date}}</h5>
                        </div>
                        <div class="col-lg-3">
                            <label for="">Change Status</label> <br>
                            <label class="switch">
                                @if($swdeals->status == '0')
                                <input type="checkbox" checked class="productStock" data-id="{{$swdeals->id}}">
                                @elseif($swdeals->status == '1')
                                <input type="checkbox" class="productStock" data-id="{{$swdeals->id}}">
                                @endif
                                <span class="slider round "></span>
                            </label>
                        </div>
                        <div class="col-lg-3 ">
                            <label for="">Action</label><br>

                            <a href="javascript:void(0)" class="px-1"><i class="material-icons md-delete_forever text-danger btnDelete" id="{{$swdeals->id}}"></i></a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Barcode</th>
                                <th>Price</th>
                                <th>Discount Type</th>
                                <th>Discount</th>
                                <th>Assign Discount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $strewisepro = App\Models\DealProduct::where('deal_id', $swdeals->id)->get(); ?>
                            @foreach($strewisepro as $spr)
                            <?php
                            $product = App\Models\Product::where('stock', 'yes')->where('id', $spr->product_id)->first();
                            if ($product) {
                                $discounted = $product->price * $swdeals->discount / 100;
                                $dprice = $product->price - $discounted;
                            }

                            ?>

                            <tr>
                                @if($product)
                                <td>{{$product->name}}</td>
                                <td>{{$product->barcode}}</td>
                                <td>AED {{$dprice}}</td>
                                @endif
                                <form id='deals_form{{$spr->id}}'>
                                    <input type="hidden" name="id" value="{{$spr->id}}" />
                                    @csrf
                                    <td>
                                        <select name="discount_type" class="form-control" required>
                                            <option value=""selected disabled>Select Type</option>
                                            <option value="flat" {{$spr->discount_type == 'flat' ? 'selected' : ''}}>Flat</option>
                                            <option value="percentage" {{$spr->discount_type == 'percentage' ? 'selected' : ''}}>Percentage</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="discount" value="{{$spr->discount}}">
                                    </td>
                                    <td>
                                        
                                        <button type="submit" class="btn btn-md rounded font-sm hover-up" id="btnSubmit"><i class="fas fa-spinner fa-spin" style="display: none;"></i>Update</button>
                                    </td>
                                </form>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        @endforeach


    </div>
    </div>
</section>
@endsection
@section('scripts')

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.4.2/jquery.twbsPagination.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function(e) {
        $(document).on('change', '.productStock', function(e) {
            var status = $(this).prop('checked') == true ? 0 : 1;
            var data_id = $(this).attr('data-id');
            $.ajax({
                type: "POST",
                dataType: "json",
                url: '/api/change/alldealproduct/status',
                data: {
                    status: status,
                    data_id: data_id
                },
                success: function(response) {
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])

                    }
                }
            });
        })
    });
    $(document).on('click', '.btnDelete', function(e) {
        var id = $(this).attr('id')
        Swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this Deal!",
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
                        url: '/api/deal/delete/' + id,
                        type: "delete",
                        dataType: "JSON",
                        success: function(response) {

                            if (response["status"] == "fail") {
                                Swal.fire("Failed!", "Failed to delete deal.",
                                    "error");
                            } else if (response["status"] == "success") {
                                Swal.fire("Deleted!", "Deal has been deleted.",
                                    "success");
                                location.reload();
                                Count()
                            }
                        },
                        error: function(error) {
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
<script>
    $(document).ready(function(e) {
        
        $("form[id^='deals_form']").on('submit', (function(e) {
           console.log('s0s')
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: '/api/deal/update',
                type: "POST",
                data: new FormData(form[0]),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    form.find("#btnSubmit").attr('disabled', true);
                    form.find(".fa-spin").css('display', 'inline-block');
                },
                complete: function() {
                    form.find("#btnSubmit").attr('disabled', false);
                    form.find(".fa-spin").css('display', 'none');
                },
                success: function(response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"]);
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"]);
                        // window.location.href = "/brand/list";
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