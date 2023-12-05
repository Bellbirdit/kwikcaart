@extends('frontend/layout/master')
@section('title')
Kwikcaart | Shop
@endsection
@section('frontend/content')

<div class="tab-pane fade active show" id="dashboard" role="tabpanel" aria-labelledby="dashboard-tab">


<section class="container mt-50" style="max-width:700px">
    <div class="content-header">
        <div>
            <h1 class="mb-3">Personal detail</h1>
        </div>
    </div>

    <div class="card1">
        <div class="card-header">
            <h5 class="mb-0 h6">Basic Info</h5>
        </div>
        <div class="card-body">
            <input type="hidden" name="id" value="{{ $profile->id }}" />
            <form id="profile_form">
                <input type="hidden" name="id" />
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Your name</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" placeholder="Your name" name="name" id="name"
                            value="{{ $profile->name }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Your Email</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" placeholder="Your email" name="email" id="email"
                            value="{{ $profile->email }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Your Phone</label>
                    <div class="col-md-8">
                        <input type="text" class="form-control" placeholder="Your Phone" name="contact" id="contact"
                            value="{{ $profile->contact }}">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Photo</label>
                    <div class="col-md-8">
                        <input type="file" name="avatar" id="avatar" value="{{ $profile->avatar }}"
                            class="selected-files">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-4 col-form-label">Address</label>
                    <div class="col-md-8">
                        <textarea name="address" id="address" cols="30" rows="5"
                            style="width:100%">{{ $profile->address }}</textarea>
                    </div>
                </div>

                <div class="form-group mb-0 text-end">
                    <button type="submit" class="btn btn-md rounded font-sm hover-up" id="btnSubmit"><i
                            class="fas fa-spinner fa-spin" style="display: none;"></i> Update Profile</button>
                </div>
            </form>
        </div>
    </div>
    
</section>

</div>
@endsection
@section('scripts')

<script src="/js/mapInput.js">
    
</script>
<script>
    $(document).ready(function () {
        $("#latitudeArea").addClass("d-none");
        $("#longtitudeArea").addClass("d-none");
    });

</script>
<script>
    google.maps.event.addDomListener(window, 'load', initialize);

    function initialize() {
        var input = document.getElementById('autocomplete');
        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            $('#latitude').val(place.geometry['location'].lat());
            $('#longitude').val(place.geometry['location'].lng());
            $("#latitudeArea").removeClass("d-none");
            $("#longtitudeArea").removeClass("d-none");
        });
    }

</script>




<script>
    $(document).ready(function (e) {


        $("#profile_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/profile/update',
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
                    }
                    window.location.href = "/user/dashboard" + '/';
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }));
    });

</script>


@endsection