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

    <div class="col-xs-12 col-md-12" id="view-all-users-main">

        {!! Form::open(['route' => 'user.search', 'role' => 'search']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-12 col-md-12">
                        <i class="fa fa-users fa-3x header-icon"></i>
                        <h4>Edit Users</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                @if($totalUsers == 0)
                    <p>No users found..</p>
                @else
                    <div class="row table-options">
                        <div class="pull-right">
                            {!! Form::button('Search', ['class' => 'btn btn-custom btn-sm', 'type' => 'submit']) !!}
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">

                            @include('pages.main.partials.table_header', $columns)

                            <tbody>
                            @foreach($users as $user)
                                <tr class="users-row">
                                    <td>{!! $user->first_name !!}</td>
                                    <td>{!! $user->last_name !!}</td>
                                    <td>{!! $user->email !!}</td>
                                    <td>{!! isset($user->active) == true ? 'Yes' : 'No' !!}</td>
                                    <td>{!! $user->role_names !!}</td>
                                    <td>{!! Tools::dateConverter($user->created_at) !!}</td>
                                    <td>{!! Tools::dateConverter($user->updated_at) !!}</td>
                                    <td class="text-center"><a href="{!! URL::route('user.view', $user->id) !!}" class="edit-link"><i class="fa fa-pencil-square-o fa-lg"></i></a></td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {!! Form::close() !!}

        {!! $users->appends($link)->render() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            // All users functionalities
            loadAllUsersJs();
        });

    </script>

    @include('layout.main.viewer')
@stop
