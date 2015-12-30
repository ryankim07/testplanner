{{--
|--------------------------------------------------------------------------
| Button partial
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

            {!! Form::button($btnText, ['class' => 'btn ' . ' ' . $class, 'id' => $id]) !!}
        </div>
    </div>
</div>