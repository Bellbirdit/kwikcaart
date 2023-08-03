@extends('layout/master')
@section('title')
Safeer | BulkUpload
@endsection
@section('content')

<section class="content-main">
    <div class="content-header">
        <div>
            <h2 class="content-title card-title">Bulk Upload</h2>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-4 p-3">
            <div class="card">
                <header class="card-header">
                    <h5>Product Bulk Upload</h5>
                </header>
                <div class="card-body">
                    <form action="">
                        <div class="mb-2">
                            <label for="product_name" class="form-label">CSV</label>
                            <input type="file" class="form-control" name="category_excel" id="bulkuploadProduct"
                                accept=".xls, .xlsx, .csv" />
                        </div>
                        <div class="mb-2">
                            <a href="javascript:;" class="btn btn-success text-white btnbulkImportproduct"><i
                                    class="fas fa-spinner fa-spin fa-prbuimport" style="display:none;"></i> Upload &
                                Save</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 p-3">
            <div class="card">
                <header class="card-header">
                    <h5>Trending Categories</h5>
                </header>
                <div class="card-body">
                    <form action="">
                        <div class="mb-2">
                            <label for="product_name" class="form-label">CSV</label>
                            <input type="file" class="form-control" name="category_excel" id="importCat"
                                accept=".xls, .xlsx, .csv" />
                        </div>
                        <div class="mb-2">
                            <a href="javascript:;" class="btn btn-success text-white btncategoryImport"><i
                                    class="fas fa-spinner fa-spin fa-import" style="display:none;"></i> Upload &
                                Save</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 p-3">
            <div class="card">
                <header class="card-header">
                    <h5>Brand Bulk Upload</h5>
                </header>
                <div class="card-body">
                    <form action="">
                        <div class="mb-2">
                            <label for="product_name" class="form-label">CSV</label>
                            <input type="file" class="form-control" name="brands_excel" id="brandImport"
                                accept=".xls, .xlsx, .csv" />
                        </div>
                        <div class="mb-2">
                            <a href="javascript:;" class="btn btn-success text-white btnbrandImport"><i
                                    class="fas fa-spinner fa-spin fa-brand" style="display:none;"></i> Upload & Save</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- <div class="col-md-4 p-3">
            <div class="card">
                <header class="card-header">
                    <h5>Product wise offer Upload</h5>
                </header>

                <div class="card-body">
                    <div class="mb-2">
                        <label for="product_name" class="form-label">CSV</label>
                    </div>
                    <div class="mb-2">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 p-3">
            <div class="card">
                <header class="card-header">
                    <h5>Categories wise offer Upload</h5>
                </header>

                <div class="card-body">
                    <form action="">
                        <div class="mb-2">
                            <label for="product_name" class="form-label">CSV</label>
                            <input type="file" placeholder="Type here" class="form-control" id="product_name" />
                        </div>
                        <div class="mb-2">
                            <button type="submit" class="btn btn-success text-white">Upload & Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 p-3">
            <div class="card">
                <header class="card-header">
                    <h5>Brand wise offer Upload</h5>
                </header>

                <div class="card-body">
                    <form action="">
                        <div class="mb-2">
                            <label for="product_name" class="form-label">CSV</label>
                            <input type="file" placeholder="Type here" class="form-control" id="product_name" />
                        </div>
                        <div class="mb-2">
                            <button type="submit" class="btn btn-success text-white">Upload & Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div> -->
        <div class="col-md-4 p-3">
            <div class="card">
                <header class="card-header">
                    <h5>Store wise Stock Update</h5>
                </header>
                <div class="card-body">
                    <form action="">
                        <div class="mb-2">
                            <label for="product_name" class="form-label">CSV</label>
                            <input type="file" class="form-control" name="category_excel" id="storewiseUpdate"
                                accept=".xls, .xlsx, .csv" />
                        </div>
                        <div class="mb-2">
                            <a href="javascript:;" class="btn btn-success text-white btnstorewiseUpdate"><i
                                    class="fas fa-spinner fa-spin fa-srprimport" style="display:none;"></i> Upload &
                                Save</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4 p-3">
            <div class="card">
                <header class="card-header">
                    <h5>All Store Price & Stock</h5>
                </header>
                <div class="card-body">
                    <form action="">
                        <div class="mb-2">
                            <label for="product_name" class="form-label">CSV</label>
                            <input type="file" class="form-control" name="product_excel" id="importFile"
                                accept=".xls, .xlsx, .csv" />

                        </div>
                        <div class="mb-2">
                            <a class="btn btn-success text-white btnImport"> <i class="fas fa-spinner fa-spin fa-import"
                                    style="display:none;"></i> Upload & Save</a>

                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="col-md-4 p-3">
            <div class="card">
                <header class="card-header">
                    <h5>Product Bulk Delete</h5>
                </header>
                <div class="card-body">
                    <form action="">
                        <div class="mb-2">
                            <label for="product_name" class="form-label">CSV</label>
                            <input type="file" class="form-control" name="product_excel" id="importFiledel"
                                accept=".xls, .xlsx, .csv" />

                        </div>
                        <div class="mb-2">
                            <a class="btn btn-success text-white btnImportdel"> <i class="fas fa-spinner fa-spin fa-import"
                                    style="display:none;"></i> Upload & Save</a>

                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</section>
@endsection
@section('scripts')

<script>
    $(document).on('click', '.btnImportdel', function () {
        $("#importFiledel").click();
    });
    $("#importFiledel").on('change', (function (e) {
        photoError = 0;
        if (e.target.files && e.target.files[0]) {
            importFiledel = e.target.files[0];
            if (e.target.files[0].name.match(/\.(csv|xls|xlsx)$/)) {
                $("#photo-error").empty();
                photoError = 0;
                var reader = new FileReader();
                reader.readAsDataURL(e.target.files[0]);
                importDel()
            } else {
                photoError = 1;
                toastr.error('Failed', 'Please select only csv, xls, xlsx format!');
                return;
            }
        } else {
            toastr.error('Failed', 'Failed');
            return;
        }
    }));

    function importDel(file) {
        var formData = new FormData();
        if (importFiledel != "") {
            formData.append('file', importFiledel);
        } else {
            toastr.error('Failed', 'File Not Found');
            return;
        }
        $.ajax({
            url: '/api/product/bulkdelete',
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            cache: false,
            beforeSend: function () {
                $(".btnImportdel").attr('disabled', true);
                $(".fa-import").css('display', 'inline-block');
            },
            complete: function () {
                $(".btnImportdel").attr('disabled', false);
                $(".fa-import").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

</script>



<script>
    $(document).on('click', '.btnbrandImport', function () {
        $("#brandImport").click();
    });
    $("#brandImport").on('change', (function (e) {
        photoError = 0;
        if (e.target.files && e.target.files[0]) {
            brandImport = e.target.files[0];
            if (e.target.files[0].name.match(/\.(csv|xls|xlsx)$/)) {
                $("#photo-error").empty();
                photoError = 0;
                var reader = new FileReader();
                reader.readAsDataURL(e.target.files[0]);
                importBrand()
            } else {
                photoError = 1;
                toastr.error('Failed', 'Please select only csv, xls, xlsx format!');
                return;
            }
        } else {

            toastr.error('Failed', 'Failed');
            return;
        }
    }));

    function importBrand(file) {
        var formData = new FormData();
        if (brandImport != "") {
            formData.append('file', brandImport);
        } else {
            toastr.error('Failed', 'File Not Found');
            return;
        }
        $.ajax({
            url: '/api/brand/import',
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            cache: false,
            beforeSend: function () {
                $(".btnImport").attr('disabled', true);
                $(".fa-brand").css('display', 'inline-block');
            },
            complete: function () {
                $(".btnImport").attr('disabled', false);
                $(".fa-brand").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

</script>


<script>
    $(document).on('click', '.btncategoryImport', function () {
        $("#importCat").click();
    });
    $("#importCat").on('change', (function (e) {
        photoError = 0;
        if (e.target.files && e.target.files[0]) {
            importCat = e.target.files[0];
            if (e.target.files[0].name.match(/\.(csv|xls|xlsx)$/)) {
                $("#photo-error").empty();
                photoError = 0;
                var reader = new FileReader();
                reader.readAsDataURL(e.target.files[0]);
                importCategory()
            } else {
                photoError = 1;
                toastr.error('Failed', 'Please select only csv, xls, xlsx format!');
                return;
            }
        } else {
            toastr.error('Failed', 'Failed');
            return;
        }
    }));

    function importCategory(file) {
        var formData = new FormData();
        if (importCat != "") {
            formData.append('file', importCat);
        } else {
            toastr.error('Failed', 'File Not Found');
            return;
        }
        $.ajax({
            url: '/api/category/import',
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            cache: false,
            beforeSend: function () {
                $(".btnImport").attr('disabled', true);
                $(".fa-import").css('display', 'inline-block');
            },
            complete: function () {
                $(".btnImport").attr('disabled', false);
                $(".fa-import").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

</script>
<script>
    $(document).on('click', '.btnImport', function () {
        $("#importFile").click();
    });
    $("#importFile").on('change', (function (e) {
        photoError = 0;
        if (e.target.files && e.target.files[0]) {
            importFile = e.target.files[0];
            if (e.target.files[0].name.match(/\.(csv|xls|xlsx)$/)) {
                $("#photo-error").empty();
                photoError = 0;
                var reader = new FileReader();
                reader.readAsDataURL(e.target.files[0]);
                importProduct()
            } else {
                photoError = 1;
                toastr.error('Failed', 'Please select only csv, xls, xlsx format!');
                return;
            }
        } else {
            toastr.error('Failed', 'Failed');
            return;
        }
    }));

    function importProduct(file) {
        var formData = new FormData();
        if (importFile != "") {
            formData.append('file', importFile);
        } else {
            toastr.error('Failed', 'File Not Found');
            return;
        }
        $.ajax({
            url: '/api/product/updateimport',
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            cache: false,
            beforeSend: function () {
                $(".btnImport").attr('disabled', true);
                $(".fa-import").css('display', 'inline-block');
            },
            complete: function () {
                $(".btnImport").attr('disabled', false);
                $(".fa-import").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

</script>

<!-- product import -->
<script>
    $(document).on('click', '.btnbulkImportproduct', function () {
        $("#bulkuploadProduct").click();
    });
    $("#bulkuploadProduct").on('change', (function (e) {
        photoError = 0;
        if (e.target.files && e.target.files[0]) {
            bulkuploadProduct = e.target.files[0];
            if (e.target.files[0].name.match(/\.(csv|xls|xlsx)$/)) {
                $("#photo-error").empty();
                photoError = 0;
                var reader = new FileReader();
                reader.readAsDataURL(e.target.files[0]);
                importProductbulk()
            } else {
                photoError = 1;
                toastr.error('Failed', 'Please select only csv, xls, xlsx format!');
                return;
            }
        } else {

            toastr.error('Failed', 'Failed');
            return;
        }
    }));

    function importProductbulk(file) {
        var formData = new FormData();
        if (bulkuploadProduct != "") {
            formData.append('file', bulkuploadProduct);
        } else {
            toastr.error('Failed', 'File Not Found');
            return;
        }
        $.ajax({
            url: '/api/product/import',
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            cache: false,
            beforeSend: function () {
                $(".btnbulkImportproduct").attr('disabled', true);
                $(".fa-prbuimport").css('display', 'inline-block');
            },
            complete: function () {
                $(".btnbulkImportproduct").attr('disabled', false);
                $(".fa-prbuimport").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

</script>
<!-- update store wise produts -->
<script>
    $(document).on('click', '.btnstorewiseUpdate', function () {
        $("#storewiseUpdate").click();
    });
    $("#storewiseUpdate").on('change', (function (e) {
        photoError = 0;
        if (e.target.files && e.target.files[0]) {
            storewiseUpdate = e.target.files[0];
            if (e.target.files[0].name.match(/\.(csv|xls|xlsx)$/)) {
                $("#photo-error").empty();
                photoError = 0;
                var reader = new FileReader();
                reader.readAsDataURL(e.target.files[0]);
                importProductstore()
            } else {
                photoError = 1;
                toastr.error('Failed', 'Please select only csv, xls, xlsx format!');
                return;
            }
        } else {
            toastr.error('Failed', 'Failed');
            return;
        }
    }));

    function importProductstore(file) {
        var formData = new FormData();
        if (storewiseUpdate != "") {
            formData.append('file', storewiseUpdate);
        } else {
            toastr.error('Failed', 'File Not Found');
            return;
        }
        $.ajax({
            url: '/api/product/storeimport',
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: "JSON",
            cache: false,
            beforeSend: function () {
                $(".btnstorewiseUpdate").attr('disabled', true);
                $(".fa-srprimport").css('display', 'inline-block');
            },
            complete: function () {
                $(".btnstorewiseUpdate").attr('disabled', false);
                $(".fa-srprimport").css('display', 'none');
            },
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr.error('Failed', response["msg"])
                } else if (response["status"] == "success") {
                    toastr.success('Success', response["msg"])
                }
            },
            error: function (error) {
                console.log(error);
            }
        });
    }

</script>
@endsection
