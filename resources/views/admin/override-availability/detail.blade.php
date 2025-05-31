@extends('layouts.app')
@section('title', 'Profile Unavailability Detail')
@section('header-css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/data-table/css/jquery.dataTables.css') }}">
@endsection

@section('content')
<div class="container">
    <div class="card mt-5">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title fw-bold mb-0">
                <i class="bi bi-list me-2"></i> Profile : <strong> {{ $profile->title }}</strong> - Weekly Over-ride Availability List
            </h4>
            <a class="btn btn-secondary btn-sm fw-bold" title="Create New" href="{{ route('service-profiles.index') }}">
                <i class="fas fa-backward"></i> Back
            </a>
        </div>
        <div class="card-body border-top p-9">
            <div class="row">
                <div class="col-md-12">
                    {!! $dataTable->table() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer-script')
    <script type="text/javascript" charset="utf8" src="{{ asset('assets/data-table/js/jquery.dataTables.js') }}"></script>

    @if(isset($dataTable))
        {!! $dataTable->scripts() !!}
    @endif
@endsection