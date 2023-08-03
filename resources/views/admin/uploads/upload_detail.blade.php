@extends('layout/master')

@section('title')
Upload detail
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
                        <img src="{{asset('assets/imgs/brands/vendor-2.png')}}" class="center-xy img-fluid" alt="Logo Brand" />
                    </div>
                </div>
                <!--  col.// -->
                <div class="col-xl col-lg">
                    <h3>Noodles Co.</h3>
                    <p>3891 Ranchview Dr. Richardson, California 62639</p>
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
                        Manager: Jerome Bell <br />
                        info@example.com <br />
                        (229) 555-0109, (808) 555-0111
                    </p>
                </div>
                <!--  col.// -->
                <div class="col-sm-6 col-lg-4 col-xl-4">
                    <h6>Address</h6>
                    <p>
                        Country: California <br />
                        Address: Ranchview Dr. Richardson <br />
                        Postal code: 62639
                    </p>
                </div>
                <!--  col.// -->
                <div class="col-sm-6 col-xl-4 text-xl-end">
                    <map class="mapbox position-relative d-inline-block">
                        <img src="{{asset('assets/imgs/misc/map.jpg')}}" class="rounded2" height="120" alt="map" />
                        <span class="map-pin" style="top: 50px; left: 100px"></span>
                        <button
                            class="btn btn-sm btn-brand position-absolute bottom-0 end-0 mb-15 mr-15 font-xs">Large</button>
                    </map>
                </div>
                <!--  col.// -->
            </div>
            <!--  row.// -->
        </div>
        <!--  card-body.// -->
    </div>
    <!--  card.// -->
</section>
@endsection
