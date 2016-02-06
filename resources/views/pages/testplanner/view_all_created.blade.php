{{--
|--------------------------------------------------------------------------
| Created list
|--------------------------------------------------------------------------
|
| This template is used when showing all plans built by admin.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="view-all-created-main">

        {!! Form::open(['route' => 'plan.search.created', 'class' => 'form-horizontal', 'role' => 'search']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-10 col-md-10">
                        <i class="fa fa-cogs fa-3x header-icon"></i>
                        <h4>Built Plans</h4>
                    </div>
                    @if($role == "root")
                    <div class="col-xs-2 col-md-2">
                        {!! Form::select('admin', $adminsList, isset($userId) ? $userId : null, ['class' => 'form-control input-sm', 'id' => 'admin', 'data-url' => route('plan.view.all.created', ['id' => null])]) !!}
                    </div>
                    @endif
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
                                    <td>{!! $plan->description !!}</td>
                                    <td>{!! $plan->first_name !!}</td>
                                    <td>{!! $plan->last_name !!}</td>

                                    <?php
                                        if($plan->status == 'complete') {
                                            $trLabel = 'label-default';
                                        } else if($plan->status  == 'progress') {
                                            $trLabel = 'label-warning';
                                        } else {
                                            $trLabel = 'label-success';
                                        }
                                    ?>

                                    <td class="text-center"><span class="label {!! $trLabel !!}">{!! $plan->status !!}</span></td>
                                    <td>{!! Tools::dateConverter($plan->created_at) !!}</td>
                                    <td>{!! Tools::dateConverter($plan->updated_at) !!}</td>
                                    <td class="text-center"><a href="{!! URL::route('plan.view', $plan->id) !!}" class="edit-link"><i class="fa fa-pencil-square-o fa-lg"></i></a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>{!! config('testplanner.messages.plan.no_plans_found') !!}</p>
                @endif
            </div>
        </div>

        {!! Form::close() !!}

        {!! $plans->appends('')->render() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            planCreatedDates();
        });

    </script>

@stop