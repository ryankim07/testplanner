{{--
|--------------------------------------------------------------------------
| Submit button partial
|--------------------------------------------------------------------------
|
| This template is used when showing submit button.
|
--}}

    <div class="form-group">
        <div class="clearfix">
            <div class="{!! $direction !!}">

                <?php
                    $class = !isset($class) ? '' : $class;
                    $id    = !isset($id) ? '' : $id;
                ?>

                {!! Form::submit($submitBtnText, ['class' => 'btn ' . $class, 'id' => $id]) !!}

            </div>
        </div>
    </div>