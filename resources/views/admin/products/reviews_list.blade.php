@extends('layout/master')

@section('title')
Reviews list
@endsection



@section('content')

<style>
.comments-area {
    background: transparent;
    border-top: 1px solid #ececec;
    padding: 45px 0;
    padding-top: 45px;
    margin-top: 50px;
}

.comments-area .comment-list .single-comment:not(:last-child) {
    border-bottom: 1px solid #ececec;
}

.comments-area .comment-list .single-comment {
    margin: 0 0 15px 0;
    margin-bottom: 15px;
    border: 1px solid #f2f2f2;
    border-bottom-color: rgb(242, 242, 242);
    border-bottom-style: solid;
    border-bottom-width: 1px;
    border-radius: 15px;
    padding: 20px;
    -webkit-transition: 0.2s;
    transition: 0.2s;
}

.comments-area .comment-list:last-child {
    padding-bottom: 0px;
}

.product-rate {
    background-image: url("/frontend/imgs/theme/rating-stars.png");
    background-position: 0 -12px;
    background-repeat: repeat-x;
    height: 12px;
    width: 60px;
    transition: all 0.5s ease-out 0s;
    -webkit-transition: all 0.5s ease-out 0s;
}

.product-rating {
    height: 12px;
    background-repeat: repeat-x;
    background-image: url("/frontend/imgs/theme/rating-stars.png");
    background-position: 0 0;
}
</style>


<section class="content-main">
    <div class="comments-area">
        <div class="row">
            <div class="col-lg-8">
                <h4 class="mb-30">Customer Reviews    </h4>
                <?php $status = App\Models\Review::where('product_id',$id)->pluck('status')->first(); ?>
                    @if($status == 0)
                        <a href="javascript:;" class="btn btn-success text-white mb-2 change_status" id="{{$id}}" status="{{$status}}"> Activate All Reviews </a>
                    @else 
                        <a href="javascript:;" class="btn btn-danger text-white mb-2  change_status" id="{{$id}}" status="{{$status}}"> DeActivate All Reviews </a>
                    @endif
                <div class="comment-list">

                    @foreach($reviews as $review)

                    <?php
                    
                    if($review->rating == '1')
            {
                $width=20;
            }elseif($review->rating == '2')
            {
                $width=40;
            }
            elseif($review->rating == '3')
            {
                $width=60;
            }
            elseif($review->rating == '4')
            {
                $width=80;
            }
            elseif($review->rating == '5')
            {
                $width=100;
            }
                    
                    
                    
                    ?>
                    <div class="single-comment justify-content-between  mb-30">
                        <div class="user justify-content-between ">
                            <div class="thumb ">
                                <img src="assets/imgs/blog/author-2.png" alt="" />
                                <a href="#" class="fs-6 text-brand"> Customer name : {{ucwords($review->name)}}</a>  
                                <a href="javascript:;" title="Delete Review" id="{{$review->id}}" class="fs-6 text-danger delete_review"> 
                                    <i class="material-icons md-delete_forever"></i>
                                 </a>
                            </div>
                            <div class="desc mt-2">
                                <div class="d-flex justify-content-between mb-10">
                                    <div class="d-flex align-items-center">
                                        <span class="fs-6 text-muted"> Review Date :
                                            {{$review->created_at->format('M d , Y h:m a')}}
                                        </span>
                                    </div>


                                </div>
                                <p class="mb-10 fs-6"> Commment : {{ucwords($review->review)}} </p>
                            </div>
                        </div>
                        <div class="product-rate d-inline-block">
                            <div class="product-rating" style="width: {{$width}}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
</section>
@endsection

@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>



$(document).on('click', '.change_status', function () {
       
        var id = $(this).attr('id');
        var status = $(this).attr('status');
        $.ajax({
            url: "/reviews/status",
            type: "get",
            data:{status:status,id:id},
            dataType: "JSON",
            cache: false,
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {} else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                     location.reload()
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    });
    $(document).on('click', '.delete_review', function(e) {
            var id = $(this).attr('id')
            Swal.fire({
                    title: "Are you sure?",
                    text: "You will not be able to recover this Review!",
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
                            url: '/reviews/delete/' + id,
                            type: "get",
                            dataType: "JSON",
                            success: function(response) {

                                if (response["status"] == "fail") {
                                    Swal.fire("Failed!", "Failed to delete review.",
                                        "error");
                                } else if (response["status"] == "success") {
                                    Swal.fire("Deleted!", "Review has been deleted.",
                                        "success");
                                        location.reload();
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

@endsection