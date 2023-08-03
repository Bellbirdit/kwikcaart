@extends('layout/master')
@section('title')
Amor Rozgar | Add Ticket
@endsection
@section('content')

<div class="row">
    <div class="col-lg-2 col-md-4 col-sm-4 col-xs-4  mt-2 mb-1">
        <a class="btn btn-primary" href="/store/ticket">
            <i class="fa fa-arrow-left" aria-hidden="true"></i> Back To Ticket
        </a>
    </div>
</div>
<?php
$roles = App\Models\User::whereHas('roles',function($s){
    $s->where('name','!=','Admin');
})->get();
?>
<div class="row corner1">
    <div class="row ml-34" id="corner" style="width: 94%; margin-top:15px;">
        <div class="col-lg-3"></div>
        <div class="col-lg-12">
            <div class="card mt-2" style="box-shadow:none; border: 1px solid #e9ebec; margin-left:0px;">
                <div class="card-header">Open new ticket</div>
                <div class="card-body mt-1">
                    <!-- dropzone start-->
                    <div class="form-group col-lg-12" id="drop_form">
                        <form id="form_dropzone" action="/api/upload/files" class="dropzone" data-id="from_dropzone"
                            role="form" enctype="multipart/form-data">
                            @csrf
                            <div class="fallback">
                                <input name="file" type="file" id="dropFile" />
                            </div>
                        </form>
                    </div>
                    <div class="form-group col-lg-12" id="drop_note">
                        <div class="row">
                            <div class="col-md-2"></div>
                            <div class="col-md-8">
                                <span class="text-danger" id="uploadError" style="display:none;"></span><br>
                                <span id="uploadMsg">{{__('')}}</span><br>
                                <span id="uploadMsg">{{__('')}}</span>
                            </div>
                            <div class="col-md-2"></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-12" id="drop_upload">
                        <div class="row">

                            <div class="col-md-12">
                                <table id="myTable" class="table table-sm">
                                    <thead>
                                        <!-- <tr>
                                                    <td>{{__('')}}</td>
                                                </tr> -->
                                    </thead>
                                    <tbody id="uploadedFiles">
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    <!-- dropzone end-->
                    <form class="form form-horizontal" id="AddticketForm">
                        <div class="row">


                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label" for="fname-icon">Subject<span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <input type="text" id="subject" class="form-control" name="subject"
                                                placeholder="Enter subject" required />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="status" value="close" id="">
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label" for="message-icon">Message<span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <textarea class="form-control" name="message" id="" cols="30" rows="5"
                                                required></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-1 row">
                                    <div class="col-sm-3">
                                        <label class="col-form-label" for="message-icon">Select User<span
                                                class="text-danger">*</span></label>
                                    </div>
                                    <div class="col-sm-9">
                                        <div class="input-group input-group-merge">
                                            <select name="user_id" class="form-control" required>
                                                @foreach($roles as $role)
                                                <option value="{{$role->id}}">{{ucwords($role->email)}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="attachment" id="filesArrss" value="">





                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" id="Addticket" class="btn btn-primary me-1 .btn-relief-{color}"><i
                                        class="fa fa-spinner fa-spin" style="display: none;"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-3"></div>
    </div>
</div>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
$(document).ready(function(e) {
    var isRtl = $('html').attr('data-textdirection') === 'rtl';


    //for addticket

    $("#AddticketForm").on('submit', (function(e) {
        e.preventDefault();
        $.ajax({
            url: '/api/admin/ticket/add',
            type: "POST",
            data: new FormData(this),
            dataType: "JSON",
            processData: false,
            contentType: false,
            cache: false,
            beforeSend: function() {
                $("#Addticket").attr('disabled', true);
                $(".fa-spin").css('display', 'inline-block');
            },
            complete: function() {
                $("#Addticket").attr('disabled', false);
                $(".fa-spin").css('display', 'none');
            },
            success: function(response) {
                console.log(response);
                if (response["status"] == "fail") {
                    toastr['error'](
                        response["msg"],
                        'Error!', {
                            closeButton: true,
                            tapToDismiss: false,
                            rtl: isRtl
                        });

                } else if (response["status"] == "success") {
                    toastr['success'](
                        response["msg"],
                        'Success!', {
                            closeButton: true,
                            tapToDismiss: false,
                            rtl: isRtl
                        });


                    $("#AddticketForm")[0].reset();
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }));
})
</script>
<!-- dropzone -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
//DropZone
Dropzone.autoDiscover = false;
$(document).ready(function() {
    var filesUploaded = [];
    var filesHtml = '';
    var validExtensions = [".jpg", ".jpeg", ".png", ".PNG", ".svg", ".word", ".PDF", ".pdf", ".xls", ".csv"];
    var myDropzone = new Dropzone("#form_dropzone", {
        parallelUploads: 1,
        uploadMultiple: false,
        timeout: 18000000,
        maxFilesize: 20, // MB
        maxFiles: 5,
        acceptedFiles: ".jpg,.jpeg,.png,.PNG,.svg,.pdf,.PDF,.csv,.xls,.word,.docs",
        addRemoveLinks: true,
        autoProcessQueue: true,
        init: function() {

            this.on('queuecomplete', function(file, response) {
                // Here you can go to next form/route
                uploadedFiles()
            })

            this.on("sending", function(file, xhr, formData) {
                //console.log(file)
            })

            this.on("complete", function(file, response) {
                //console.log(response)
            })

            this.on("success", function(file, response) {
                this.removeFile(file);
                //var res = JSON.parse(response)
                if (response["status"] == "success") {
                    filesUploaded.push(response["file"]["file"]);
                }
            })

            this.on("uploadprogress", function(file, progress, bytesSent) {
                //console.log(file, progress, bytesSent)
            })

            this.on('errormultiple', function(file, response) {
                // Here you can go to next form/route
                //alert(response)
            })

            this.on('error', function(file, response) {
                // Here you can go to next form/route
                // alert(response)
                console.log(response)

            })
        },
        maxfilesreached: function(file) {
            $("#uploadError").html('Please upload max 5 file.');
        },
        maxfilesexceeded: function(file) {
            $("#uploadError").html('Please upload max 5 file.');
        },
        removedfile: function(file) {
            var fileName = file.name;
            var _ref;
            return (_ref = file.previewElement) != null ? _ref.parentNode.removeChild(file
                .previewElement) : void 0;
        }
    });

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    $("#btnUpload").click(function(e) {
        e.preventDefault();
        $("#uploadError").hide();
        //$("#btnUpload").attr('disabled', true);
        if (myDropzone.getRejectedFiles().length == 0) {
            if (myDropzone.getAcceptedFiles().length == 0) {
                $("#uploadError").show();
                $("#uploadError").html('Please select files to upload.');
            } else {
                $("#uploadError").hide();
                for (var i = 0; i < myDropzone.getAcceptedFiles().length; i++) {
                    myDropzone.processFile(myDropzone.getAcceptedFiles()[i]);
                }
            }
        } else {
            $("#uploadError").show();
            $("#uploadError").html('Please upload valid files.');
        }
    });

    function processDropzonePhotos() {
        $("#uploadError").hide();
        //$("#btnUpload").attr('disabled', true);

        console.log(myDropzone.getRejectedFiles().length)

        if (myDropzone.getRejectedFiles().length == 0) {
            if (myDropzone.getAcceptedFiles().length == 0) {
                $("#uploadError").show();
                $("#uploadError").html('Please select files to upload.');
            } else {
                $("#uploadError").hide();
                for (var i = 0; i < myDropzone.getAcceptedFiles().length; i++) {
                    myDropzone.processFile(myDropzone.getAcceptedFiles()[i]);
                }
            }
        } else {
            $("#uploadError").show();
            $("#uploadError").html('Please upload valid files.');
        }
    }

    function uploadedFiles() {
        var html = '';
        if (filesUploaded.length > 0) {
            $.each(filesUploaded, (i, f) => {

                html += '<tr>' +
                    '<td><span><b>' + f.name + '</b><br><small>Uploaded At: ' + f.date +
                    ' | Size: ' + formatBytes(f.size, decimals = 2) + '</small></span></td>' +
                    '<td><button type="button" id="' + f.filename +
                    '" class="btnDeleteFile btn btn-sm btn-danger w-100"><i class="fa fa-trash"></i></button> </td></tr>';
            })
            $("#uploadedFiles").empty()
            $("#uploadedFiles").html(html)

            $("#filesArrss").val(JSON.stringify(filesUploaded))
        } else {
            $("#uploadedFiles").empty()
            $("#uploadedFiles").html('No files are uploaded yet')
            $("#filesArrss").val('')
        }
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';

        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

        const i = Math.floor(Math.log(bytes) / Math.log(k));

        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    function removeUploadedFile(file) {
        var newArr = [];

        $.each(filesUploaded, (i, f) => {
            if (f.filename != file) {
                newArr.push(f)
            }
        })

        filesUploaded = newArr;
        uploadedFiles()
    }
    $(document).on('click', '.btnDeleteFile', function(e) {
        var file = $(this).attr('id');
        console.log(file)
        Swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this Image!",
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
                        url: '/api/delete/files',
                        type: "delete",
                        dataType: "JSON",
                        data: {
                            file: file,
                        },
                        success: function(response) {
                            if (response["status"] == "fail") {
                                Swal.fire("Failed!", "Failed to delete file.",
                                    "error");
                            } else if (response["status"] == "success") {
                                Swal.fire("Deleted!", "File has been deleted.",
                                    "success");
                                removeUploadedFile(file)

                            }
                        },
                        error: function(error) {
                            console.log(error);
                        },
                        async: false
                    });
                } else {
                    Swal.close();
                }
            });
    });



});
</script>
<!-- end dropzone-->
@endsection