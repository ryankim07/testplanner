{{--
|--------------------------------------------------------------------------
| Admin registration
|--------------------------------------------------------------------------
|
| This template is used when showing admin registration form.
|
--}}

    {!! Form::open(['route' => 'user.update', 'class' => 'form-horizontal', 'id' => 'user-form-update']) !!}
    {!! Form::hidden('user_id', $user->id) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="clearfix">
                <div class="pull-left">
                    <h3>View User</h3>
                </div>
                <div class="pull-right">
                    <button type="button" class="close close-viewer" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            </div>
        </div>
        <div class="panel-body">

            <div class="alert" role="alert"></div>

            @include('pages/main/partials/user_info', [
                'column'               => 'col-md-8',
                'user'                 => $user,
                'rolesOptions'         => $rolesOptions,
                'rolesSelectedOptions' => $rolesSelectedOptions
            ])

            @include('pages/main/partials/button', [
                'btnText'   => 'Update',
                'direction' => 'pull-left',
                'class'     => 'btn btn-success btn-sm',
                'id'        => 'update-btn'
            ])

        </div>
    </div>

    {!! Form::close() !!}

    <script type="text/javascript">

        $(document).ready(function() {
            $('.alert').hide();

            $('#view-user-main').on('click', '#update-btn', function() {
                var newRoles = $("#current_roles").val() || [];

                $.ajax({
                    method: "POST",
                    url: "{!! URL::to('user/update') !!}",
                    data: $("#user-form-update").serialize() + '&new_roles=' + newRoles,
                    dataType: "json"
                }).done(function (response) {
                    var msgs = '';

                    $('.alert').attr('class', 'alert');
                    $('.alert').empty();

                    if (response.type == 'success') {
                        $('.alert').attr('class', 'alert alert-success').html('<span class="glyphicon glyphicon-ok-sign" aria-hidden="true"></span><span class="sr-only">Success:</span> ' + response.msg).show();
                    } else {
                        $.each(response.msg, function(key, item) {
                            msgs += '<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span> ' + item + '<br/>';
                        });

                        $('.alert').attr('class', 'alert alert-danger').html(msgs).show();
                    }
                });
            });
        });

    </script>