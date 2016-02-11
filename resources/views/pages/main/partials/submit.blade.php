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
                <div class="{!! isset($direction) ? $direction : 'pull-left' !!}">

                    <?php
                        $class = !isset($class) ? '' : $class;
                        $id    = !isset($id) ? '' : $id;
                    ?>

                    {!! Form::submit($btnText, ['class' => 'btn ' . $class, 'id' => $id]) !!}
                </div>
            </div>
        </div>
    </div>