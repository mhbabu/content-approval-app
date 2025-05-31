@extends('layouts.app')
@section('title', 'Set Override Availability')
@section('header-css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection
@section('content')
    <div class="container mt-5">
        {!! html()->form('POST', route('override-availabilites.store'))->class('form-horizontal')->open() !!}
        <fieldset class="border p-4">
            <legend class="w-auto">Over-ride Seller Weekly Availabilites</legend>

            <div class="my-3">
                {!! html()->label('Profile')->class('form-label required')->for('profile_id') !!}
                {!! html()->select('profile_id')->options($profiles)->class('form-control')->placeholder('Select Profile') !!}
            </div>

            <table class="table table-striped table-sm override-availability-table">
                <tr>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
                <tr class="override-availability-row">
                    <td>
                        {!! html()->date('date[]')->class('form-control date-data') !!}
                    </td>
                    <td>
                        <label class="btn btn-primary btn-sm add-more"><i class="bi bi-plus-circle"></i></label>
                    </td>
                </tr>
            </table>
            <button class="float-end btn btn-primary">Save</button>
        </fieldset>
        {!! html()->form()->close() !!}
    </div>
@endsection

@section('footer-script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        /********************************
        ADD MORE WEEKLY AVAILABILITIES
        *******************************/
        $(document).on('click', '.add-more', function() {
            let row = $('.override-availability-row').eq(0).clone();
            let rowIndex = $('.override-availability-row').length;
            if (rowIndex > 9) {
                alert("Maximum 10 row allowed!");
                return false;
            }
            row.find('.add-more')
                .removeClass('add-more btn-primary')
                .addClass('btn-danger remove')
                .html('<i class="bi bi-dash-circle"></i>');

            row.find('input,select').each(function(i, input) {
                $(input).val('');
            });
            $('.override-availability-table').append(row);
        });

        $(document).on('click', '.remove', function() {
            $(this).parent().parent().remove();
        });

        /****************************************
        UINIQ DAY OF WEEK SCRIPTING START HERE
        *****************************************/
        $(document).on("change", ".date-data", function() {
            let parentHtml = $(this).parent().parent().parent().parent().parent();
            let weekDay = $(this).val();
            let totalCount = 0;
            parentHtml.find('.date-data').each(function(i, input) {
                if (input.value == weekDay) {
                    totalCount++;
                }
            });

            if (totalCount > 1) {
                alert('Please select uniq date')
                $(this).val('');
            }
        });
    </script>
@endsection
