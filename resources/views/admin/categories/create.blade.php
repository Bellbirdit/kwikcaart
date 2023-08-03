@extends('layout/master')

@section('title')
Categories
@endsection

@section('content')
<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0 h6">Category Information</h5>
            </div>
            <div class="card-body">
                <form class="form-horizontal" id="cat_form">
                	
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label">Name</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="Name" id="name" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label">Category Code</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="Category Code" id="name" name="category_code" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label">Parent Category</label>
                        <div class="col-md-9">
                            <select class="select2 form-control " name="parent_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                <option value="0">No Parent</option>
                                @foreach($categories as $cat)
                                <option value="{{$cat->id}}">{{ucwords($cat->name)}}</option>
                                @endforeach
  
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label">
                            Ordering Number
                        </label>
                        <div class="col-md-9">
                            <input type="number" name="order_level" class="form-control" id="order_level" placeholder="Order Level">
                            <small>Higher number has high priority</small>
                        </div>
                    </div>

                    <input type="hidden" name="digital" value="0">
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">Banner <small>(200x200)</small></label>
                        <div class="col-md-9">
                            <input type="file" name="banner" class="form-control">
                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label" for="signinSrEmail">Icon<small>(32x32)</small></label>
                        <div class="col-md-9">
                        <input type="file" name="icon" class="form-control">

                            <div class="file-preview box sm">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row mb-2" >
                        <label class="col-md-3 col-form-label">Meta Title</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" name="meta_title" placeholder="Meta Title">
                        </div>
                    </div>

                    <div class="form-group row mb-2" >
                        <label class="col-md-3 col-form-label">Meta Description</label>
                        <div class="col-md-9">
                            <textarea name="meta_description" rows="5" class="form-control"></textarea>
                        </div>
                    </div>

    <!-- 
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label">Commission Rate</label>
                        <div class="col-md-9">
                            <input type="number" min="0" step="0.01" placeholder="Commission Rate" id="commision_rate" name="commision_rate" class="form-control">

                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label">Filtering Attributes</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="filtering_attributes[]" data-toggle="select2" data-placeholder="Choose ..."data-live-search="true" multiple>
                                @foreach ($attributes as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> -->
                    <div class="form-group row mb-2">
                        <div class="col-md-9">
                           <input type="checkbox"  name="is_featured" id="is_featured" value="0">Featured
                        </div>
                    </div>
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fas fa-spinner fa-spin" style="display: none;"></i> Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            closeOnSelect: true
        });
    });
</script>
<script>
    $(document).ready(function(e) {

        $(document).on('change', '.imageChange', function(e) {
            photoError = 0;
            if (e.target.files && e.target.files[0]) {
                // console.log(e.target.files[0].name.strtolower())
                if (e.target.files[0].name.match(/\.(jpg|jpeg|JPG|png|gif|PNG)$/)) {
                    $("#photo-error").empty();
                    photoError = 0;
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#user_photo_selected').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(e.target.files[0]);

                } else {
                    photoError = 1;
                    $("#photo-error").empty();
                    $("#btnSubmit").prop('disabled', true);

                    $("#photo-error").append(
                        '<p class="text-danger">Please upload only jpg, png format!</p>');
                }
            } else {
                $('#user_photo_selected').attr('src', '');
            }

            if (photoError == 0) {
                $(".btn-change-photo").prop('disabled', false);
                $("#btnSubmit").attr('disabled', false);
            } else {
                $(".btn-change-photo").prop('disabled', true);
            }
        });

        $("#cat_form").on('submit', (function(e) {
            e.preventDefault();

            if ($('#is_featured').is(':checked')) {

                var featured = "1";
            }else{

                var featured = "0"
            }

            var formData = new FormData(this);
            formData.append('featured',featured);
            $.ajax({
                url: '/api/category/add',
                type: "POST",
                data: formData,
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
                        location.reload();
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }));
    });
</script>
<script>
    $(document).ready(function() {
        $('.country').on('change', function() {
            var idCountry = this.value;
            $(".state").html('');
            $.ajax({
                url: "{{url('api/fetch-states')}}",
                type: "POST",
                data: {
                    country_id: idCountry,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(result) {
                    $('.state').html('<option value="">Select State</option>');
                    $.each(result.states, function(key, value) {
                        $(".state").append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                    $('.city').html('<option value="">Select City</option>');
                }
            });
        });
        $('.state').on('change', function() {
            var idState = this.value;
            $(".city").html('');
            $.ajax({
                url: "{{url('api/fetch-cities')}}",
                type: "POST",
                data: {
                    state_id: idState,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function(res) {
                    $('.city').html('<option value="">Select City</option>');
                    $.each(res.cities, function(key, value) {
                        $(".city").append('<option value="' + value
                            .id + '">' + value.name + '</option>');
                    });
                }
            });
        });
    });
</script>
@endsection