<div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
    <section class="content-main">
        @php
            $profile = App\Models\User::where('id', auth()->user()->id)->get();
        @endphp
        <div class="main-container container-fluid">
            
            <div class="card1">
                @foreach($profile as $pro)
                    <div class="card-body bg-white">
                        <h1 class="mb-3">My Profile</h1>
                        <div class="row">
                            <div class="col-2 my-auto">
                                @if($pro->avatar != '')
                                    <img class="br-5 rounded-circle w-100" alt=""src="{{ asset('/uploads/files/'.$pro->avatar) }}">
                                @else
                                    <img class="br-5" alt=""src="{{ asset('assets/imgs/people/avatar-2.png') }}">
                                @endif
                            </div>
                            <div class="col-10">
                                <a href="/edit/profile/{{ $pro->id }}"
                                    class="btn btn-success float-end text-white">Edit
                                    profile</a>
                                <p class="tx-13 text-muted ms-md-4 ms-0 mb-2 pb-2 ">
                                    <span class="me-3">
                                        <i class="far fa-address-card me-2"></i>
                                        {{ $pro->name }}
                                    </span>
                                </p>
                                <p class="text-muted ms-md-4 ms-0 mb-2">
                                    <span><i class="fa fa-mobile me-2"></i></span>
                                    <span class="font-weight-semibold me-2">Phone: {{ $pro->contact }}</span>
                                </p>
                                <p class="text-muted ms-md-4 ms-0 mb-2">
                                    <span><i class="fa fa-envelope me-2"></i></span>
                                    <span class="font-weight-semibold me-2">Email:</span>
                                    {{ $pro->email }}
                                </p>
                                <p class="text-muted ms-md-4 ms-0 mb-2">
                                    <span><i class="fa fa-envelope me-2"></i></span>
                                    <span class="font-weight-semibold me-2">Address:</span>
                                    {!! nl2br($pro->address) !!}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="col-lg-12 col-12">
                <div class="card">
                    
                    <div class="card-body">
                        <div class="card-header  text-end">
                            <a href="javascript:;" class="btn btn-org btn-icon  mt-3" data-bs-target="#addnewAddress "
                                data-bs-toggle="modal">
                                Add Address<i class="fas fa-plus tx-12"></i>
                            </a>
                        </div>
                        <div class="row ">
                            @php
                                $address = App\Models\UserAddress::where('user_id', auth()->user()->id)->get();
                            @endphp
                            @foreach($address as $ad)
                
                                <div class="col-md-6 ">
                                    <div class="card borderd border border-success">
                                        <div class="card-header row">
                                            <div class="col-md-8">
                                                <h6 class="mb-0"> {{$ad->address_type}}</h6>
                                            </div>
                                            <div class="col-md-2">
                                            @if($ad->is_default=='1')
                                                <label class="switch">
                                                    <input type="checkbox" checked class="defaultAddress" data-id="{{ $ad->id }}">
                                                    <span class="slider round"></span>
                                                </label>
                                            @else
                                                <label class="switch">
                                                    <input type="checkbox" class="defaultAddress" data-id="{{ $ad->id }}">
                                                    <span class="slider round"></span>
                                                </label>
                                            @endif
                                            </div>
                                            <div class="col-md-2">
                                               
                                                  <a href="javascript:void(0)" class="px-1"><i
                                        class="material-icons md-delete_forever text-danger btnnDelete"
                                        id="{{ $ad->id }}"></i></a>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div>
                                                {!! nl2br($ad->address) !!}
                                            </div>
                                            @if($ad->building_name != '')
                                             <div>
                                                Building Name : {{$ad->building_name}}
                                            </div>
                                            @endif
                                             @if($ad->flat_name != '')
                                             <div>
                                                Flat No : {{$ad->flat_name}}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-12 mt-4">
                <div class="card1">
                    <div class="card-header bg-light">
                        <div class="h6">Change Password</div>
                    </div>
                    <div class="card-body bg-white">
                        <form id="change_password">
                            @csrf
                            <div class="form-group">
                               
                                <div>
                                    <input class=" form-control" type="password" required name="current_password"
                                        placeholder="Current Password">
                                </div>
                            </div>
                            <div class="form-group">
                               
                                <div>
                                    <input class=" form-control" type="password" required name="new_password"
                                        placeholder="New Password">
                                </div>
                            </div>
                            <div class="form-group">
                               
                                <div>
                                    <input class="input100 form-control" type="password" required
                                        name="confirm_password" placeholder="Confirm Password">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12 text-center mt-3">
                                    <button type="submit" id="btnSubmitPassword"
                                        class="btn btn-success text-white px-4">
                                        <i class="fa fa-spin fa-spinner" id="bx-pass" style="display: none"></i> Save
                                        Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
    </section>
    <div class="modal fade" id="addnewAddress">
        <div class="modal-dialog m-center" role="document" style="transform: translateX(-50%); left: 50%; bottom: 5%;">
            <div class="modal-content modal-content-demo">
                <div class="modal-header">
                    <h6 class="modal-title">New Address</h6><button aria-label="Close" class="btn-close"
                        data-bs-dismiss="modal" type="button"></button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal text-center" id="address_form">
                        
                         <div class="form-group">
                            <!--<label>Address Type</label>-->
                            <input  type="text" name="address_type" id="" class="form-control" placeholder="Home, Office etc..." required />
                        </div>
                        <div class="form-group" id="map" style="height: 100px; width: 100%;"></div>
                        <div class="form-group">
                            <!--<label>Address</label>-->
                            <input type="text" id="autocomplete" placeholder="Enter your address"  name="address" autocomplete="off">
                        </div>
                        
                        <button type="button" id="btnGetCurrentLocation" onclick="showMyLocation()" class="btn btn-primary ">Get Current Location</button>
                        
                        <div class="form-group">
                            <!--<label>Building Name</label>-->
                            <input  type="text" name="building_name" id="" class="form-control" placeholder="Building Name"  />
                        </div>
                        
                        <div class="form-group">
                            <!--<label>Flat No</label>-->
                            <input  type="text" name="flat_name" id="" class="form-control" placeholder="Flat No"  />
                        </div>
                        
                        <button type="submit" class="btn "  id="btnSubmit"><i class="fas fa-spinner fa-spins "style="display: none; text-align:right;"></i>Save Addres</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTjwHs1Na-t4-r_oMK8PXwYoYqU-dtM24&libraries=places&callback=initAutocomplete"></script> -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDTjwHs1Na-t4-r_oMK8PXwYoYqU-dtM24&callback=initMap"
        async defer></script>


<script>
    var map;
    function initMap() {
        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: 0, lng: 0},
            zoom: 18
        });
    }
    function showMyLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude;
                var lng = position.coords.longitude;
                var myLocation = new google.maps.LatLng(lat, lng);
                map.setCenter(myLocation);
                var marker = new google.maps.Marker({
                    position: myLocation,
                    map: map
                });
            });
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }
</script>





<script>
  document.addEventListener('DOMContentLoaded', function() {
    initAutocomplete();
    document.getElementById('btnGetCurrentLocation').addEventListener('click', getCurrentLocation);
  });
</script>





<script>
  var autocomplete;

  function initAutocomplete() {
    autocomplete = new google.maps.places.Autocomplete(
      document.getElementById('autocomplete'),
      { types: ['geocode'] }
    );

    autocomplete.addListener('place_changed', getAddress);
  }

  function getAddress() {
    var place = autocomplete.getPlace();

    var address = place.formatted_address;

    document.getElementById('autocomplete').value = address;
  }

  function getCurrentLocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var geocoder = new google.maps.Geocoder();

        var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);

        geocoder.geocode({ location: latlng }, function(results, status) {
          if (status === 'OK') {
            document.getElementById('autocomplete').value = results[0].formatted_address;
          } else {
            alert('Geocode was not successful for the following reason: ' + status);
          }
        });
      }, function() {
        alert('Geolocation failed');
      });
    } else {
      alert('Geolocation is not supported by this browser');
    }
  }

  document.getElementById('btnGetCurrentLocation').addEventListener('click', getCurrentLocation);
</script>


<script>
    $(document).ready(function (e) {
        $(document).on('change', '.defaultAddress', function (e) {
            var is_default = $(this).prop('checked') == true ? '1' : '0';
            var data_id = $(this).attr('data-id');

            $.ajax({
                type: "POST",
                dataType: "json",
                url: '/api/change/address/status',
                data: {
                    is_default: is_default,
                    data_id: data_id
                },
                success: function (response) {
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])

                    }
                }
            });
        })
    });

</script>



<script>
    $(document).ready(function () {
        $('#onloadModal').modal({
            backdrop: 'static',
            keyboard: false
        })
    });

</script>

<script>
    $(document).on('click', '.select_category', function () {
        $(".append_category").html('')
        $('.not_found').css('display', 'none');
        var id = $(this).attr('id');
        var level = $(this).attr('level');
        console.log(level);
        $.ajax({
            url: "/sub/category",
            type: "get",
            data: {
                level: level,
                id: id
            },
            dataType: "JSON",
            cache: false,
            success: function (response) {
                console.log(response);
                if (response["status"] == "fail") {} else if (response["status"] == "success") {
                    if (response['html'] == '') {
                        $('.not_found').css('display', 'inline-block');

                    } else {
                        $(".append_category").html(response['html'])
                    }
                }
            },
            error: function (error) {
                console.log(error);
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

<script>
    $(document).ready(function (e) {

        $("#cat_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/userprofile/update',
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
                },
                error: function (error) {
                    console.log(error);
                }
            });
        }));
    });

</script>

<script>
    $(document).ready(function (e) {

        // change Password
        $("#change_password").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/change/password',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $("#btnSubmitPassword").attr('disabled', true);
                    $("#bx-pass").css('display', 'inline-block');
                },
                complete: function () {
                    $("#btnSubmitPassword").attr('disabled', false);
                    $("#bx-pass").css('display', 'none');
                },
                success: function (response) {
                    // console.log(response);
                    if (response["status"] === "fail") {
                        toastr.error('Failed', response["msg"]);
                    } else if (response["status"] === "success") {
                        toastr.success('Success', response["msg"]);
                        $("#change_password")[0].reset();
                    }

                },
                error: function (error) {
                    console.log(error);
                }
            });
        }));
    });

</script>

<script>
    $(document).ready(function (e) {
        $("#address_form").on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: '/api/store/address',
                type: "POST",
                data: new FormData(this),
                dataType: "JSON",
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function () {
                    $("#btnSubmit").attr('disabled', true);
                    $(".fa-spins").css('display', 'inline-block');
                },
                complete: function () {
                    $("#btnSubmit").attr('disabled', false);
                    $(".fa-spins").css('display', 'none');
                },
                success: function (response) {
                    console.log(response);
                    if (response["status"] == "fail") {
                        toastr.error('Failed', response["msg"])
                    } else if (response["status"] == "success") {
                        toastr.success('Success', response["msg"])
                        $("#address_form")[0].reset();
                        location.reload();
                    }
                },
                error: function (error) {
                    // console.log(error);
                }
            });
        }));
    });
  $(document).on('click', '.btnnDelete', function (e) {
        var id = $(this).attr('id')
        Swal.fire({
                title: "Are you sure?",
                text: "You will not be able to recover this address!",
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
                        url: '/api/address/delete/' + id,
                        type: "delete",
                        dataType: "JSON",
                        success: function (response) {

                            if (response["status"] == "fail") {
                                Swal.fire("Failed!", "Failed to delete address.",
                                    "error");
                            } else if (response["status"] == "success") {
                                Swal.fire("Deleted!", "Address has been deleted.",
                                    "success");
                                    location.reload();
                                Count()
                                 
                            }
                        },
                        error: function (error) {
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
