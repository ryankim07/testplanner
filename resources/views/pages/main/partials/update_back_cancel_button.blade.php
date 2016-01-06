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
                        $updateBtnId = !isset($updateBtnId) ? '' : $updateBtnId;
                        $backBtnId   = !isset($backBtnId) ? '' : $backBtnId;
                    ?>

                    @if(!empty($backBtnText))
                        {!! Form::submit($backBtnText, ['class' => 'btn ' . $class, 'id' => $backBtnId]) !!}
                    @endif
                    {!! Form::submit($updateBtnText, ['class' => 'btn ' . $class, 'id' => $updateBtnId]) !!}

                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#back-btn').on('click', function () {
                window.location.href = '{!! URL::previous() !!}}';
            });
        });
    </script>