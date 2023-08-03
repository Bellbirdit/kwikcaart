@extends('layout/master')
@section('title')
Safeer Market | Notifications
@endsection
@section('content')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="left-content">
        <span class="main-content-title mg-b-0 mg-b-lg-1">View Notifications</span>
    </div>
    <div class="justify-content-center mt-2">
        <ol class="breadcrumb">
            <li class="breadcrumb-item tx-15"><a href="/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Notifications</li>
        </ol>
    </div>
</div>
<!-- /breadcrumb -->

<!-- Row -->
<div class="row row-sm">
    <div class="col-lg-12">
        <div class="card custom-card overflow-hidden">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-nowrap border-bottom" id="not-datatable">
                        <thead>
                            <tr>
                                <th>Notification</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($message as $notification)
                            <tr>
                                <td><a href="{{ $notification['link'] }}">{{ $notification['message'] }}</a></td>
                                <td>{{ $notification['time'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')

<script>
    $('#not-datatable').DataTable({
		language: {
			searchPlaceholder: 'Search...',
			sSearch: '',
		},
		order: [],
	});
</script>
@endsection