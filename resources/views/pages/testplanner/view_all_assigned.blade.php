{{--
|--------------------------------------------------------------------------
| Assigned list
|--------------------------------------------------------------------------
|
| This template is used when showing all assigned plans by other admins
| and current viewer needs to respond.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="view-all-assigned-main">

        {!! Form::open(['route' => 'plan.search', 'role' => 'search']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <i class="fa fa-commenting-o fa-3x header-icon"></i>
                        <h4>Plans assigned to me</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                @if(count($plans))
                    <div class="row table-options">
                        <div class="pull-right">
                            {!! Form::button('Search', ['class' => 'btn btn-custom btn-sm', 'type' => 'submit']) !!}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">

                            @include('pages.main.partials.table_header', $columns)

                            <tbody>
                            @foreach($plans as $plan)
                                <tr class="plan-row">
                                    <td>
                                        {!! $plan->description !!}
                                    </td>
                                    <td>{!! $plan->first_name !!}</td>
                                    <td>{!! $plan->last_name !!}</td>
                                    <td>{!! Tools::dateConverter($plan->created_at) !!}</td>
                                    <td>{!! Tools::dateConverter($plan->updated_at) !!}</td>
                                    <td class="text-center"><a href="{!! URL::route('plan.respond', $plan->id) !!}"><i class="fa fa-commenting-o fa-lg"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>{!! config('testplanner.messages.plan.no_plans_assigned') !!}</p>
                @endif
            </div>
        </div>

        {!! Form::close() !!}

        {!! $plans->appends('')->render() !!}

    </div>

@stop