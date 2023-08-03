@extends('layout/master')

@section('title')
Store detail
@endsection

@section('content')
<section class="content-main">
    <!-- <div class="content-header">
        <a href="javascript:history.back()"><i class="material-icons md-arrow_back"></i> Go back </a>
    </div> -->
    <div class="card mb-4">
        <div class="card-header bg-brand-2" style="height: 150px"></div>
        <div class="card-body">
            <div class="row">
                <div class="col-xl col-lg flex-grow-0" style="flex-basis: 230px">
                    <div class="img-thumbnail shadow w-100 bg-white position-relative text-center"
                        style="height: 190px; width: 200px; margin-top: -120px">
                        <img src="{{asset('uploads/files/'.$store->avatar)}}" class="center-xy img-fluid" alt="store img" />
                    </div>
                </div>
                <!--  col.// -->
                <div class="col-xl col-lg">
                    <h3>{{ $store->name}}</h3>
                    <p>{{ $store->address}}, {{ $store->city}} {{$store->country}}</p>
                </div>
                <!--  col.// -->
                
                <!--  col.// -->
            </div>
            <!-- card-body.// -->
            <hr class="my-4" />
            <div class="row g-4">
                
                <!--  col.// -->
                <div class="col-sm-6 col-lg-4 col-xl-4">
                    <h6>Contacts</h6>
                    <p>
                        Manager: ------ <br />
                        {{$store->email}} <br />
                        {{$store->contact}}
                    </p>
                </div>
                <!--  col.// -->
                <div class="col-sm-6 col-lg-4 col-xl-4">
                    <h6>Address</h6>
                    <p>
                        Country: United Arab Emirates <br />
                        Address: {{$store->address}} <br />
                        Store code: {{$store->code}}
                    </p>
                </div>
                <!--  col.// -->
                <!-- <div class="col-sm-6 col-xl-4 text-xl-end">
                    <map class="mapbox position-relative d-inline-block">
                        <img src="{{asset('assets/imgs/misc/map.jpg')}}" class="rounded2" height="120" alt="map" />
                        <span class="map-pin" style="top: 50px; left: 100px"></span>
                        <button
                            class="btn btn-sm btn-brand position-absolute bottom-0 end-0 mb-15 mr-15 font-xs">Large</button>
                    </map>
                </div> -->
                <!--  col.// -->
            </div>
            <!--  row.// -->
        </div>
        <!--  card-body.// -->
    </div>
    <!--  card.// -->
</section>
@endsection
