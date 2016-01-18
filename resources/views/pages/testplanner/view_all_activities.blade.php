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

        {!! Form::open(['route' => 'activity.search', 'class' => 'form-horizontal', 'role' => 'search']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <i class="fa fa-terminal fa-3x header-icon"></i>
                        <h4>Activity Streams <span class="badge">{!! $totalActivities !!}</span></h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                @if($totalActivities > 0)
                    <div class="row table-options">
                        <div class="pull-right">
                            {!! Form::button('Search', ['class' => 'btn btn-custom btn-sm', 'type' => 'submit']) !!}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">

                            @include('pages.main.partials.table_header', $columns)

                            <tbody>
                            @foreach($activities as $stream)
                                <tr>
                                    <td>{!! $stream->activity !!}</td>
                                    <td>{!! Utils::dateConverter($stream->created_at) !!}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No activities found.</p>
                @endif
            </div>
        </div>

        {!! Form::close() !!}

        {!! $activities->appends($link)->render() !!}

    </div>

@stop