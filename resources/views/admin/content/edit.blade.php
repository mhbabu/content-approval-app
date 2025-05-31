@extends('layout.master')
@section('title', 'Add New Designation')
@section('content')
<div class="px-4">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h5> <i class="fa fa-plus-circle"></i> {{ __('Create Designation') }}</h5>
            </div>

            {{ html()->form('POST', route('categories.store'))->class('form-horizontal')->open() }}
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-6">
                        {{ html()->label('Occasion')->class('form-label required')->for('occasion') }}
                        {{ html()->text('occasion')->class('form-control')->placeholder('occasion')->attribute('maxlength', 191)->required()->autofocus() }}
                    </div>
                    <div class="col-md-6">
                        {{ html()->label('Date')->class('form-label required')->for('date') }}
                        {{ html()->text('date')->class('form-control')->placeholder('date')->attribute('maxlength', 191)->required()->autofocus()->id('date')->placeholder('YYYY-MM-DD')->autocomplete('off') }}
                    </div>
                    <div class="col-md-6">
                        {{ html()->label('Status')->class('form-label required')->for('status') }}
                        {{ html()->select('status')
                        ->options([1 => 'Active', 0 => 'Inactive'])
                        ->class('form-control')
                        ->attribute('maxlength', 191)
                        ->required() }}
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row mb-0">
                    <div class="col-md-6 text-start">
                        <a href="{{ route('holidays.index') }}" class="btn btn-secondary btn-sm"> {{
                            __('Back')
                            }} </a>
                    </div>
                    <div class="col-md-6 text-end">
                        <button type="submit" class="btn btn-primary btn-sm"> {{ __('Save') }} </button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
</div>
@endsection
@section('footer-script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        const currentDate   = new Date();
        const endOfYearDate = new Date(currentDate.getFullYear(), 11, 31);

        $("#date").flatpickr({
            minDate: currentDate,
            maxDate: endOfYearDate,
            dateFormat: "Y-m-d"
        });
    });
</script>

@endsection