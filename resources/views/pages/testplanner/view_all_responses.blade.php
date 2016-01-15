{{--
|--------------------------------------------------------------------------
| Responses list
|--------------------------------------------------------------------------
|
| This template is used when showing all responses by testers.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="view-all-responses-main">

        {!! Form::open(['route' => 'plan.search', 'class' => 'form-horizontal', 'role' => 'search']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <i class="fa fa-comments fa-3x header-icon"></i>
                        <h4>Plans assigned to others <span class="badge">{!! $totalPlans !!}</span></h4>
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
                                    <td>{!! $plan->description !!}</td>
                                    <td>{!! $plan->full_name !!}</td>

                                    <?php
                                        if($plan->status == 'complete') {
                                            $trLabel = 'label-default';
                                        } else if($plan->status  == 'progress') {
                                            $trLabel = 'label-warning';
                                        } else {
                                            $trLabel = 'label-success';
                                        }
                                    ?>

                                    <td class="text-center"><span class="label {!! $trLabel !!}">{!! $plan->status !!}</span</td>
                                    <td>{!! Utils::dateConverter($plan->created_at) !!}</td>
                                    <td>{!! Utils::dateConverter($plan->updated_at) !!}</td>
                                    <td>{!! Form::select('testers', $testers[$plan->id], null, ['class' => 'form-control input-sm testers', 'data-url' => route('plan.view.response', $plan->id)]) !!}</td>
                                    <td class="text-center"><a href="#" class="plan-link"><i class="fa fa-comments fa-lg"></i></a></td>
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

    <script type="text/javascript">

        $(document).ready(function() {
            $('#view-all-responses-main .plan-row').each(function() {
                var testerId = $(this).find('.testers option:nth-child(1)').val();
                var route    = $(this).find('.testers').data('url') + '/' + testerId;
                var link     = $(this).find('.plan-link').prop('href', route);
            });

            // Change viewer id link
            $('#view-all-responses-main').on('change', '.testers', function() {
                var selectedTesterId = $(this).val();
                var route = $(this).data('url') + '/' + selectedTesterId;

                $(this).closest('td').next('td').find('.plan-link').prop('href', route);
            });
        });

    </script>

@stop