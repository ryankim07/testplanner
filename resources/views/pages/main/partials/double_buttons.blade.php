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
                        $class = !isset($class) ? '' : $class;
                    ?>

                    {!! Form::button($cancelBtnText, ['class' => 'btn ' . ' ' .  $cancelClass, 'id' => $cancelBtnId]) !!}
                    {!! Form::button($opBtnText, ['class' => 'btn ' . ' ' . $opClass, 'id' => $opBtnId]) !!}
                </div>
            </div>
        </div>
    </div>