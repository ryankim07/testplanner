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
                        <h4>Plans assigned to me  <span class="badge">{!! $totalPlans !!}</span></h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                @if($totalPlans > 0)
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
                                    <td>{!! $plan->full_name !!}</td>

                                    <?php
                                        if($plan->ticket_response_status == 'complete') {
                                            $trLabel = 'label-default';
                                        } else if($plan->ticket_response_status == 'progress') {
                                            $trLabel = 'label-warning';
                                        } else {
                                            $trLabel = 'label-success';
                                        }
                                    ?>

                                    <td class="text-center"><span class="label {!! $trLabel !!}">{!! $plan->ticket_response_status !!}</span></td>
                                    <td>{!! Utils::dateConverter($plan->created_at) !!}</td>
                                    <td>{!! Utils::dateConverter($plan->updated_at) !!}</td>
                                    <td class="text-center"><a href="{!! URL::route('plan.respond', $plan->id) !!}"><i class="fa fa-commenting-o fa-lg"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>No plans found..</p>
                @endif
            </div>
        </div>

        {!! Form::close() !!}

        {!! $plans->appends($link)->render() !!}

    </div>

@stop