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
                        $btnClass    = !isset($btnClass) ? '' : $btnClass;
                        $btnId       = !isset($btnId) ? '' : $btnId;
                        $submitClass = !isset($submitClass) ? '' : $submitClass;
                        $submitId    = !isset($submitId) ? '' : $submitId;
                        $data        = !isset($btnDataName) && !isset($btnData) ? '' : $btnDataName . '=>' .  $btnData;
                    ?>

                    {!! Form::button($btnText, ['class' => 'btn ' . $btnClass, 'id' => $btnId, $data]) !!}
                    {!! Form::submit($submitText, ['class' => 'btn ' . $submitClass, 'id' => $submitId]) !!}
                </div>
            </div>
        </div>
    </div>