@extends('layout/master')

@section('title')
Safeer | Categories
@endsection

@section('content')
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
                    <input type="hidden" name="id" value="{{ $category->id }}" />

                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label">Name</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="Name" id="name" name="name" class="form-control"
                                value="{{ $category->name }}" required>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label">Category Code</label>
                        <div class="col-md-9">
                            <input type="text" placeholder="Category Code" id="code" name="category_code"
                                class="form-control" value="{{ $category->category_code }}" required>
                        </div>
                    </div>
                    <?php $categories = App\Models\Category::all(); ?>
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label">Parent Category</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="parent_id" data-toggle="select2" data-placeholder="Choose ..." data-live-search="true">
                                <option value="0"  {{ $category->parent_id == 0 ? 'selected' : '' }}>No Parent</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}"
                                                        {{ $cat->id == $category->parent_id ? 'selected' : '' }}>
                                                        {{ $cat->name }}</option>
                                @endforeach
  
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label">
                            Ordering Number
                        </label>
                        <div class="col-md-9">
                            <input type="number" name="order_level" class="form-control" id="order_level"
                                placeholder="Order Level" value="{{ $category->order_level }}">
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
                            <input type="text" class="form-control" name="meta_title" id="meta_title"
                                placeholder="Meta Title" value="{{ $category->meta_title }}">
                        </div>
                    </div>

                    <div class="form-group row mb-2" >
                        <label class="col-md-3 col-form-label">Meta Description</label>
                        <div class="col-md-9">
                            <textarea name="meta_description" rows="5"
                                class="form-control">{{ $category->meta_description }}</textarea>
                        </div>
                    </div>


                    <!-- <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label">Commission Rate</label>
                        <div class="col-md-9">
                            <input type="number" min="0" step="0.01" placeholder="Commission Rate" id="commision_rate"
                                name="commision_rate" class="form-control" value="{{ $category->commision_rate }}">

                        </div>
                    </div>
                    <?php $attributes = App\Models\Attribute::all(); ?>
                    <div class="form-group row mb-2">
                        <label class="col-md-3 col-form-label">Filtering Attributes</label>
                        <div class="col-md-9">
                            <select class="select2 form-control aiz-selectpicker" name="filtering_attributes[]"
                                data-toggle="select2" data-placeholder="Choose ..." data-live-search="true" multiple>
                                @foreach($attributes as $attribute)
                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> -->
                    <div class="form-group mb-0 text-right">
                        <button type="submit" class="btn btn-primary" id="btnSubmit"><i class="fas fa-spinner fa-spin"
                                style="display: none;"></i> Save</button>
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
    $(document).ready(function (e) {

        $("#cat_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/category/update',
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
                         window.location.href = "/category/list";
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
