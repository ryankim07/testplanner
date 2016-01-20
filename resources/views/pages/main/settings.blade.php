{{--
|--------------------------------------------------------------------------
| Response | Respond template
|--------------------------------------------------------------------------
|
| This template is used when viewing, editing plan response.
|
--}}

@extends('layout.main.master')

@section('content')

    <div class="col-xs-12 col-md-12 main" id="system-main">

        {!! Form::open(['route' => 'system.update', 'class' => 'form-horizontal', 'id' => 'system-edit-form']) !!}

        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-10 col-md-10">
                        <i class="fa fa-cogs fa-3x header-icon"></i>
                        <h4>System Configuration</h4>
                    </div>
                </div>
            </div>
            <div class="panel-body">

                @include('errors.list')

                <ul class="nav nav-pills nav-stacked col-xs-12 col-md-2">
                    @foreach($configData as $key => $value)
                        <li><a href="#{!! $key !!}" data-toggle="tab">{!! ucfirst($key) !!}</a></li>
                    @endforeach
                </ul>
                <div class="tab-content col-xs-12 col-md-10">
                    @foreach($configData as $parentKey => $child)
                        <div class="tab-pane" id="{!! $parentKey !!}">
                            <div class="page-header">
                                <h4 class="pull-left">{!! ucfirst($parentKey) !!} Settings</h4>
                                <div class="pull-right">

                                    @include('pages/main/partials/button', [
                                        'direction' => 'pull-right',
                                        'class'		=> 'btn-custom btn-sm',
                                        'btnText'	=> 'Update',
                                        'id'        => 'update-btn'
                                    ])

                                </div>
                                <div class="clearfix"></div>
                            </div>

                            @foreach($child as $childKey => $attributes)
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <strong>{!! ucfirst($childKey) !!}</strong>
                                    </div>
                                    <div class="panel-body">
                                        @foreach($attributes as $attrKey => $attrValue)

                                            <?php
                                                $inputTextName = $parentKey . '_' . $childKey . '_' . $attrKey;
                                                $dataName      = $parentKey . '_' . $childKey . ':' . $attrKey;
                                                $inputTextId   = str_replace('_', '-', $inputTextName);
                                                $typeFormatted = ucfirst(str_replace('_', ' ', $attrKey));
                                            ?>

                                            <div class="form-group">
                                                {!! Form::label($attrKey . '_label', $typeFormatted . ':', ['class' => 'control-label col-md-2']) !!}
                                                <div class="col-xs-12 col-md-6">
                                                    {!! Form::text($inputTextName, $attrValue, ['class' => 'form-control input-sm required', 'id' => $inputTextId, 'data-name' => $dataName]) !!}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {!! Form::close() !!}

    </div>

    <script type="text/javascript">

        $(document).ready(function() {
            // Activate first tab nav and tab content
            $('#system-main .nav-pills li').first().addClass('active');
            $('#system-main .tab-content div').first().addClass('active');

            // Grab all the original contents
            var inputs = $('input[type="text"]').each(function() {
                $(this).data('original', this.value);
            });

            $('#update-btn').click(function(){
                var fields = [];
                var items = {};

                // Grab token
                items['_token'] = $('form').find('input[name=_token]').val();

                // Grab only fields that were changed
                inputs.each(function() {
                    if ($(this).data('original') !== this.value) {
                        items[$(this).data('name')] = this.value;
                    }
                });

                // Update by Ajax
                if (Object.keys(items).length > 1) {
                    $.ajax({
                        method: "POST",
                        url: $('form').attr('action'),
                        data: items,
                        dataType: "json"
                    }).done(function (res) {
                        alert(res.msgs);
                    });
                } else {
                    alert('You have not changed any fields');
                }
            });
        });

    </script>

@stop