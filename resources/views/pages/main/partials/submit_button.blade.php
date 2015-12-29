{{--
|--------------------------------------------------------------------------
| Submit button partial
|--------------------------------------------------------------------------
|
| This template is used when showing submit button.
|
--}}

<div class="form-group">
    <?php
        if (empty($css)) {
            $css = 'col-xs-12 col-md-8';
        }
    ?>

    <div class="{!! $css !!}">
        {!! Form::submit($submitBtnText, ['class' => 'btn btn-primary', 'id' => 'continue-btn']) !!}
    </div>
</div>