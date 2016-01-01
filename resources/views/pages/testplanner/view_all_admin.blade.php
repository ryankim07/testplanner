{{--
|--------------------------------------------------------------------------
| Admin assigned plan list
|--------------------------------------------------------------------------
|
| This template is used when showing all plans assigned to admin.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="view-all-admin-main">

        @include('pages.testplanner.partials.table_header', [
            'table_type' => 'view-all-admin',
            'header'     => 'All plans assigned to others'
        ])

    </div>

@stop