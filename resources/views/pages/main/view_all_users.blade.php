{{--
|--------------------------------------------------------------------------
| All users list
|--------------------------------------------------------------------------
|
| This template is used when showing all users for the system.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="view-all-users-main">

        {!! Form::open(['route' => 'user.search', 'role' => 'search']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <i class="fa fa-users fa-4x header-icon"></i>
                        <h3>All users <span class="badge">{!! $totalUsers !!}</span></h3>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                @if($totalUsers == 0)
                    <p>No users found..</p>
                @else
                    <div class="row table-options">
                        <div class="pull-right">
                            {!! Form::button('Search', ['class' => 'btn btn-success', 'type' => 'submit']) !!}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">

                            @include('pages.main.partials.table_header', $columns)

                            <tbody>
                            @foreach($users as $user)
                                <tr class="toggler" data-url="{!! URL::route('user.view', $user->id) !!}">
                                    <td>{!! $user->first_name !!}</td>
                                    <td>{!! $user->last_name !!}</td>
                                    <td>{!! $user->email !!}</td>
                                    <td>{!! isset($user->active) == true ? 'Yes' : 'No' !!}</td>
                                    <td>{!! $user->role_names !!}</td>
                                    <td>{!! Utils::dateConverter($user->created_at) !!}</td>
                                    <td>{!! Utils::dateConverter($user->updated_at) !!}</td>
                                </tr>
                            @endforeach
                        </table>

                        {!! $users->appends($link)->render() !!}

                    </div>
                @endif
            </div>
        </div>

        {!! Form::close() !!}

    </div>

@stop