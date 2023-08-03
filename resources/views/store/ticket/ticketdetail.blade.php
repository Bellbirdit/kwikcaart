@extends('layout/master')
@section('title')
Amor Rozgar | Ticket Detail
@endsection
@section('content')

<div class="row">
    <div class="page-title-box">
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item"><a href="/dashboard">{{__('Dashboard')}}</a></li>
            <li class="breadcrumb-item"><a href="/store/ticket">{{__('Support tickets')}}</a></li>
            <li class="breadcrumb-item active">{{__('Ticket detail')}}</li>
        </ol>
    </div>
</div>

<div class="card-body">


    <div class="row">
        <div class="col-12">
            <div class="row">
                <div class="col-lg-6">
                    <h4>Ticket detail#{{$tickets->id}}</h4>
                </div>
                <div class="col-lg-3">
                    <span class="">By: <a href="#" class="company-name">{{ucwords($tickets->user->first_name)}}
                            {{ucwords($tickets->user->last_name)}}</a></span>

                </div>
                <div class="col-lg-3 text-end">
                    @if($tickets->status=="open")
                    <p class="card-text">Status: <span class="custom-badge badge-success">
                            {{$tickets->status}}</span></p>
                    @else
                    <p class="card-text">Status: <span class="custom-badge badge-danger"> {{$tickets->status}}</span>
                    </p>
                    @endif

                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="alert alert-success" style="background-color: white !important;" role="alert">
                        <div class="row">
                            <div class="col-lg-12">
                                <h5 class="text-bold px-1 pt-1"><b>Message</b> </h5>
                                <p class="card-text mx-1  pb-1" style="color: #767188;">
                                    {{$tickets->message}}
                                </p>
                            </div>
                            <div class="col-lg-4 d-flex">
                                @php
                                    
                                   $Annoucefiles="";
                                        if(! empty($tickets)){
                                        if($tickets->attachment != NULL){
                                            
                                            $Annoucefiles=json_decode($tickets->attachment);

                                            
                                        }else{
                                            $Annoucefiles="Empty";
                                        }
                                        }else{
                                            $Annoucefiles="Empty"; 
                                        }
                                        $imgExt= array(
                                        "png",
                                        "jpg",
                                        "jpeg",
                                        "PNG",
                                        "JPG",
                                    );
                                    
                               @endphp

                                    @if (is_array($Annoucefiles) || $Annoucefiles instanceof Traversable) 
                                        @foreach($Annoucefiles as $af)
                                        <div class="px-1 pb-1">
                                            @if(in_array($af->ext,$imgExt))
                                            <div class="  align-items-center">
                                                <img src="{{asset('/uploads/files/'.$af->filename)}}" id="myImg"
                                                    class=" rounded img-fluid product-img myImg" alt=""
                                                    style="height:80px; width:80px; " />
                                            </div>
                                            <span>
                                                <a href="/uploads/files/{{$af->filename}}" download="{{$af->name}}">
                                                    <small><i class="fa fa-download"></i> Download</small>
                                                </a>
                                            </span>
                                            @else
                                            <div class="  align-items-center">
                                                <img src="{{asset('asset/images/logo/jpg.png')}}" id="myImg"
                                                    class=" rounded img-fluid product-img myImg" alt=""
                                                    style="height:80px; width:80px; " />
                                            </div>
                                            <span>
                                                <a href="/uploads/files/{{$af->filename}}" download="{{$af->name}}">
                                                    <small><i class="fa fa-download"></i> Download</small>
                                                </a>
                                            </span>
                                            @endif


                                        </div>
                                    @endforeach

                                    @endif

                              
                                <hr>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    </div>
    <div class="row" id="commentshow">

    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal myModal">
        <span class="close">&times;</span>
        <img class="modal-content img01" id="img01">
        <div id="caption"></div>
    </div>






    <hr />
    <form class="form form-horizontal" enctype="multipart/form-data" id="AddcommentForm">
        <input type="hidden" id="supportticketid" name="support_ticket_id" value="{{$tickets->id}}">

        <div class="row mb-2">
            <div class="col-lg-8 col-sm-12 col-md-8 col-12">



                <div class="">
                    <textarea name="comment" id="commentid" class="form-control" id="" cols="3" rows="5"
                        required></textarea>
                </div>


            </div>
            <div class="col-4 col-md-4 col-sm-12 col-12">
                <div class="row mt-1">
                    <div class="col-lg-9 col-md-9 col-sm-12 col-12">
                        <div class="mb-1 row">
                            <div class="input-group input-group-merge">
                                <label class="file-label" title="Upload files"><i class="feather-paperclip"></i>
                                    <input type="file" name="upload[]" id="upload" size="60">
                                </label>
                            </div>
                            <div id="uploadFilesDiv">

                            </div>

                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                        <div class="">
                            <button type="submit" id="Addcomment" class="btn btn-primary me-1 .btn-relief-{color}"><i
                                    class="fa fa-spinner fa-spin" style="display:none;"></i> &nbsp; Add
                                comment &nbsp;<i class="fa fa-paper-plane"></i></button>

                        </div>
                    </div>
                </div>

            </div>
        </div>



    </form>


</div>


<div class="{{$tickets->id}}" id="ticketId"></div>

<!-- start modal for add comment -->
<div class="card-body">
    <div class="demo-inline-spacing">
        <div class="form-modal-ex">

            <div class="modal fade text-start" id="addclass" tabindex="-1" aria-labelledby="myModalLabel33"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel33">Add Comment</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <form class="form form-horizontal" enctype="multipart/form-data" id="AddcommentForm">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-1 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="class">Comment<span
                                                        class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="input-group input-group-merge">
                                                    <textarea name="comment" id="" cols="45" rows="5"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="support_ticket_id" value="{{$tickets->id}}">

                                        <div class="mb-1 mt-2 row">
                                            <div class="col-sm-3">
                                                <label class="col-form-label" for="image">Attachment<span
                                                        class="text-danger">*</span></label>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="input-group input-group-merge">

                                                    <input type="file" id="" class="form-control" name="image" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-9 offset-sm-3">
                                        <button type="submit" id="Addcomment"
                                            class="btn btn-primary me-1 .btn-relief-{color}"><i
                                                class="fa fa-spinner fa-spin" style="display:none;"></i> &nbsp; Add
                                            comment</button>

                                    </div>
                                </div>
                            </form>

                        </div>


                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<!-- end modal for add comment -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {

    //for add comment
    var fileCount = 0;
    var files = [];

    function uploadFileListHTML(filesize, filename) {
        var html = '<div id="row' + fileCount + '" class="row text-right"><div class="col-lg-12">';
        html += '<span class="fileName' + fileCount + '">' + filename + '(' + formatBytes(filesize) +
            ')&nbsp;</span>';
        html += '<input id="files' + fileCount + '" name="files[]" type="hidden">';
        //html += '<span><small class="fileMsg'+fileCount+'"> file uploading.. 20%</small></span>';
        html += '<span><small id="' + fileCount + '" class="filedelBtn' + fileCount +
            '"><a href="javascript:;" class="fileDelete" id="' + fileCount +
            '">&nbsp;&nbsp;<i class="fa fa-trash text-danger border-left"></i></a></small></span>';
        html += '</div></div>';
        $("#uploadFilesDiv").append(html);
        fileCount++;
        return html;
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    function messageMsg(msg) {
        $("#messageMsg").css('opacity', 1);
        $("#messageMsg").html(msg)
        setTimeout(function() {
            $("#messageMsg").css('opacity', 0);
        }, 10000);
    }
    $('#upload').on('change', function() {
        $("#btnSubmit").attr('disabled', true);
        var filesize = this.files[0].size;
        var filename = $(this).val().replace(/.*(\/|\\)/, '');
        $("#fileName").html(filename + '(' + filesize + ')');
        // output raw value of file input
        $('#filename').html($(this).val().replace(/.*(\/|\\)/, ''));

        // or, manipulate it further with regex etc.
        var filename = $(this).val().replace(/.*(\/|\\)/, '');
        // .. do your magic
        $('#filename').html(filename);
        var formData = new FormData();
        formData.append('file', this.files[0]);
        formData.append('fileCount', fileCount);
        //upload file
        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = (evt.loaded / evt.total) *
                            100;
                        //Do something with upload progress here
                        //$("#messageMsg").html('File Upload.. '+parseFloat(percentComplete).toFixed(2))
                        messageMsg('File Upload.. ' + parseFloat(
                                percentComplete)
                            .toFixed(2) +
                            '%')
                    }
                }, false);
                return xhr;
            },
            type: 'POST',
            url: '/api/file/uploads',
            data: formData,
            dataType: "JSON",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            processData: false, // tell jQuery not to process the data
            contentType: false, // tell jQuery not to set contentType
            success: function(data) {
                console.log(data)
                //Do something on success
                files.push(data["file"]);
                uploadFileListHTML(filesize, filename);
                messageMsg(data["msg"])
                $("#btnSubmit").attr('disabled', false);
                $("#upload").val('');
            },
            error: function(error) {
                console.log(error)
                $("#btnSubmit").attr('disabled', false);
            }
        });
        $('#upload').append('<input type="file" name="upload[]"/>')

    });
    $(document).on('click', '.fileDelete', function(e) {
        $("#btnSubmit").attr('disabled', true);
        var id = $(this).attr('id');
        $("#row" + id).remove();
        var file = '';
        $.each(files, (i, v) => {
            if (v.fileCount == id) {
                file = v.filename;
            }
        });

        //delete file
        $.ajax({
            type: 'GET',
            url: '/api/file/delete/',
            data: {
                files: files
            },
            dataType: "JSON",
            success: function(data) {
                //Do something on success
                files = files.filter(function(item) {
                    return item.fileCount !== id
                })
                messageMsg(data["msg"])
                $("#btnSubmit").attr('disabled', false);
            },
            error: function(error) {
                console.log(error)
                $("#btnSubmit").attr('disabled', false);
            }
        });
    });
    var isRtl = $('html').attr('data-textdirection') === 'rtl';
    //add comment
    $("#AddcommentForm").on('submit', (function(e) {
        var myFile = files
        var comment = $("#commentid").val()
        var support_ticket_id = $("#supportticketid").val()
        e.preventDefault();
        $.ajax({
            url: '/api/comments/add',
            type: "POST",
            data: {
                myFile: myFile,
                comment: comment,
                support_ticket_id: support_ticket_id
            },
            dataType: "JSON",
            cache: false,
            beforeSend: function() {
                $("#Addcomment").attr('disabled', true);
                $(".fa-spin").css('display', 'inline-block');
            },
            complete: function() {
                $("#Addcomment").attr('disabled', false);
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
                    files = [];
                    comments()
                    $("#uploadFilesDiv").html('');
                    $("#AddcommentForm")[0].reset();
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }));

    //list
    comments()

    function comments() {

        var id = $("#ticketId").attr('class');
        $("#commentshow").html('')
        $.ajax({
            url: '/api/comment/view',
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Content-Type": "application/json"
            },
            data: {
                id: id
            },
            type: "get",
            dataType: "JSON",
            cache: false,
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(response) {
                console.log(response);
                if (response["status"] == "fail") {
                    $("#divLoader").css('display', 'none')
                    $("#divData").css('display', 'block')
                } else if (response["status"] == "success") {
                    $("#divLoader").css('display', 'none')
                    $("#commentshow").css('display', 'flex')
                    $("#commentshow").html('')
                    $("#commentshow").append(response["rows"])

                }

            },

            error: function(error) {
                console.log(error);
            }
        });
    }
    //delete
    $(document).on('click', '.deletecomment', function(e) {
        var id = $(this).attr('id');
        Swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this class!",
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
                        url: '/api/comment/delete/' + id,
                        type: "get",
                        dataType: "JSON",
                        success: function(response) {

                            if (response["status"] == "fail") {
                                Swal.fire("Failed!", "Failed to delete Class.",
                                    "error");
                            } else if (response["status"] == "success") {
                                Swal.fire("Deleted!", "Class has been deleted.",
                                    "success");
                                comments()

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
})
</script>

<style>
body {
    font-family: Arial, Helvetica, sans-serif;
}

#myImg {
    border-radius: 5px;
    cursor: pointer;
    transition: 0.3s;
}

#myImg:hover {
    opacity: 0.7;
}

/* The Modal (background) */
.modal {
    display: none;
    /* Hidden by default */
    position: fixed;
    /* Stay in place */
    z-index: 1;
    /* Sit on top */
    padding-top: 100px;
    /* Location of the box */
    left: 0;
    top: 0;
    width: 100%;
    /* Full width */
    height: 100%;
    /* Full height */
    overflow: auto;
    /* Enable scroll if needed */
    background-color: rgb(0, 0, 0);
    /* Fallback color */
    background-color: rgba(0, 0, 0, 0.9);
    /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
    margin: auto;
    display: block;

    max-width: 400px;
    max-height: 450px;
}

/* Caption of Modal Image */
#caption {
    margin: auto;
    display: block;
    width: 80%;
    max-width: 500px;
    text-align: center;
    color: #ccc;
    padding: 10px 0;
    height: 150px;
}

/* Add Animation */
.modal-content,
#caption {
    -webkit-animation-name: zoom;
    -webkit-animation-duration: 0.6s;
    animation-name: zoom;
    animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
    from {
        -webkit-transform: scale(0)
    }

    to {
        -webkit-transform: scale(1)
    }
}

@keyframes zoom {
    from {
        transform: scale(0)
    }

    to {
        transform: scale(1)
    }
}

/* The Close Button */
.close {
    position: absolute;
    top: 95px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px) {
    .modal-content {
        width: 100%;
    }
}
</style>
<script>
$(document).on('click', '.myImg', function(e) {
    var id = $(this).attr('id')
    var img = $('.myImg')
    var modalImg = $(".img01");
    var captionText = $(".caption");
    // Get the modal
    var modal = $(".myModal");
    modal.css('display', 'block');
    src = $(this).attr('src');
    $(".img01").attr('src', src);



})
$(document).on('click', '.close', function() {
    $(".myModal").css('display', 'none');
})
</script>
@endsection