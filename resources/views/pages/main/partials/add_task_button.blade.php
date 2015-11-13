{{--
|--------------------------------------------------------------------------
| Submit button partial
|--------------------------------------------------------------------------
|
| This template is used when showing submit button.
|
--}}

<div class="row" id="btn-row">
    <div class="col-md-4">
        {!! Form::button($btnText, ['class' => 'green-btn task-btn', 'id' => 'add-task-btn']) !!}
    </div>
</div>