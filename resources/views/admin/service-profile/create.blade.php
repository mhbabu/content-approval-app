@extends('layouts.app')
@section('title', 'Create Profile')
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h5> <i class="fa fa-plus-circle"></i> {{ __('Create Profile') }}</h5>
            </div>

            {!! html()->form('POST', route('service-profiles.store'))->class('form-horizontal')->open() !!}
            {!! html()->hidden('timezone')->id('timezone') !!}
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-4">
                        {!! html()->label('Title')->class('form-label required')->for('title') !!}
                        {!! html()->text('title')->class('form-control')->placeholder('Title')->autofocus() !!}
                    </div>
                    <div class="col-md-4">
                        {!! html()->label('Category')->class('form-label required')->for('category_id') !!}
                        {!! html()->select('category_id')->options($categories)->class('form-control')->placeholder('Select Category')->autofocus() !!}
                    </div>
                    <div class="col-md-4">
                        {!! html()->label('Status')->class('form-label required')->for('status') !!}
                        {!! html()->select('status') ->options([1 => 'Active', 0 => 'Inactive'])->class('form-control')  !!}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row mb-0">
                    <div class="col-md-6 text-start">
                        <a href="{{ route('service-profiles.index') }}" class="btn btn-secondary btn-sm"> {{
                            __('Back')
                            }} </a>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="submit" class="btn btn-primary btn-sm"> {{ __('Save') }} </button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            {!! html()->form()->close() !!}
        </div>
    </div>
@endsection
@section('footer-script')
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment-timezone-with-data.min.js') }}"></script>
    <script>
        /*******************************************
        DYNAMICALLY TIME ZONE SCRIPTING START HERE
        ********************************************/
            $(document).ready(function() {
                const timezone = moment.tz.guess();
                $('#timezone').val(timezone);
            });
    </script>
@endsection