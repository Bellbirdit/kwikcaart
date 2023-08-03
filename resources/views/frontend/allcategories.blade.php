@extends('frontend/layout/master')
@section('title')
Safeer | All Categories
@endsection
@section('frontend/content')
<style>
    a.snd-sub {
        padding-left: 10px;
        font-weight: 700;
        font-size: 18px;
    }
    a.trd-sub {
        padding-left: 20px;
        font-weight: 100;
        font-size: 13px;
    }
</style>
<main class="main">
    <div class="container ">
    @foreach($maincategorys as $maincategory)
    @php
        $subcategories = App\Models\Category::where('parent_id',$maincategory->id)->get();
    @endphp
        <div class="row mt-5">
            <a href="{{route('cat-products',$maincategory->id)}}"><h4 class="h4 py-4">{{$maincategory->name}}</h4></a>
            @foreach($subcategories as $subcategory)
            @php
                $subsubcategorys = App\Models\Category::where('parent_id',$subcategory->id)->get();
            @endphp
            <div class="col-md-3">
                <a href="{{route('cat-products',$maincategory->id)}}" class="snd-sub d-block">{{$subcategory->name}}</a>
                @foreach($subsubcategorys as $subsubcategory)
                <a href="{{route('cat-products',$maincategory->id)}}" class="trd-sub d-block">{{$subsubcategory->name}}</a>
                @endforeach
            </div>
            @endforeach
        </div>
    @endforeach   
    </div>
</main>
@endsection
@section('scripts')

@endsection
