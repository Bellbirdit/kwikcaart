@extends('layout/master')

@section('title')
Kwikcaart | Product list
@endsection

@section('content')



<section class="content-main">
    
    <div class="content-header m-0">
        <div>
            <h2 class="content-title card-title">Product Bulk Uploads</h2>
        </div>
    </div>
    <div class="col-12 mb-3">
        <div class="table-responsive">
            <table class="table">
                <tr>
                    <th>ID</th>
                    <th>Type</th>
                    <th>Filename</th>
                    <th>Status</th>
                    <th>Processed</th>
                    <th>Date</th>
                </tr>
                @foreach($uploads as $upload)
                    <tr>
                        <td>{{ $upload->id }}</td>
                        <td>{{ $upload->type }}</td>
                        <td>{{ $upload->name }}</td>
                        <td>{{ $upload->status }}</td>
                        <td>{{ $upload->processed }}</td>
                        <td>{{ date("d F, Y", strtotime($upload->created_at)) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
    <!-- card end// -->
</section>
@endsection