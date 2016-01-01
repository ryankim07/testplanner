{{--
|--------------------------------------------------------------------------
| Admin registrations list
|--------------------------------------------------------------------------
|
| This template is used when showing all registrations.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="view-all-plans-main">

        {!! Form::open(['route' => 'plan.search', 'class' => 'form-horizontal', 'role' => 'search']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="clearfix">
                    <div class="pull-left"><h3>All Plans <span class="badge">{!! $totalPlans !!}</span></h3></div>
                    <div class="pull-right">
                        {!! Form::select('view_user_type', ['0' => 'All', Auth::user()->id => 'My Plans'], $userId, ['class' => 'form-control input-sm', 'id' => 'view-user', 'data-url' => route('plan.view.all', ['id' => null])]) !!}
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @if(count($totalPlans) > 0)

                    <div class="row table-options">
                        <div class="pull-right">
                            {!! Form::button('Search', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">

                            @include('pages.main.partials.table_header', $columns)

                            <tbody>

                            @foreach($plans as $plan)

                                <tr class="toggler" data-url="{!! URL::route('plan.view', $plan->id) !!}">
                                    <td>{!! $plan->description !!}</td>
                                    <td>{!! $plan->first_name !!}</td>
                                    <td>{!! $plan->status !!}</td>
                                    <td>{!! Utils::dateConverter($plan->created_at) !!}</td>
                                    <td>{!! Utils::dateConverter($plan->updated_at) !!}</td>
                                </tr>

                            @endforeach

                            </tbody>
                        </table>

                        {!! $plans->appends($link)->render() !!}

                    </div>

                @else

                    <p>Records not found.</p>

                @endif

            </div>
        </div>

        {!! Form::close() !!}

    </div>

@stop