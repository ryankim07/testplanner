{{--
|--------------------------------------------------------------------------
| Submit button partial
|--------------------------------------------------------------------------
|
| This template is used when showing submit button.
|
--}}

    <div class="form-group">
        <div class="col-xs-12 col-md-12">
            <div class="clearfix">
                <div class="{!! $direction !!}">

                    <?php
                        $class       = !isset($class) ? '' : $class;
                        $btnId       = !isset($btnId) ? '' : $btnId;
                        $submitBtnId = !isset($submitBtnId) ? '' : $submitBtnId;
                        $btnDataName = !isset($btnDataName) ? '' : $btnDataName;
                        $btnData     = !isset($btnData) ? '' : $btnData;
                    ?>

                    @if(!empty($btnText))
                        {!! Form::button($btnText, ['class' => 'btn ' . $class, 'id' => $btnId, $btnDataName => $btnData]) !!}
                    @endif

                    {!! Form::submit($submitBtnText, ['class' => 'btn ' . $class, 'id' => $submitBtnId]) !!}

                </div>
            </div>
        </div>
    </div>