{{--
|--------------------------------------------------------------------------
| Submit button partial
|--------------------------------------------------------------------------
|
| This template is used when showing submit button.
|
--}}

<div class="row btn-row">
    <div class="col-md-4">
        {!! Form::submit($submitBtnText, ['class' => 'green-btn step-btn', 'id' => 'continue-btn']) !!}
    </div>
</div>