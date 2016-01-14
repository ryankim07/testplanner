{{--
|--------------------------------------------------------------------------
| Testers assigned plan list
|--------------------------------------------------------------------------
|
| This template is used when showing all assigned plans to testers.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="view-all-assigned-main">

        {!! Form::open(['route' => 'plan.search', 'role' => 'search']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <h4>Plans assigned to me  <span class="badge">{!! $totalPlans !!}</span></h4>
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
                                    <td class="text-center"><a href="{!! URL::route('plan.respond', $plan->id) !!}"><i class="fa fa-pencil fa-lg"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        {!! $plans->appends($link)->render() !!}

                    </div>
                @else
                    <p>No plans found..</p>
                @endif
            </div>
        </div>

        {!! Form::close() !!}

    </div>

@stop