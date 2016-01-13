
<div class="row nested-block">
    <legend>Plan Details</legend>
    <div class="col-xs-12 col-md-3">
        <div class="form-group">
            <p>Admin: <strong>{!! $plan['reporter'] !!}</strong></p>
            <p>Assignee: <strong>{!! $plan['assignee'] !!}</strong></p>
        </div>
    </div>
    <div class="col-xs-12 col-md-3">
        <div class="form-group">
            <p>Started: <strong>{!! $plan['started_at'] !!}</strong></p>
            <p>Expires: <strong>{!! $plan['expired_at'] !!}</strong></p>
        </div>
    </div>
    <div class="col-xs-12 col-md-3">
        <div class="form-group">
            <p>Created: <strong>{!! $plan['created_at'] !!}</strong></p>
            <p>Updated: <strong>{!! $plan['updated_at'] !!}</strong></p>
        </div>
    </div>
    <div class="col-xs-12 col-md-3">
        <div class="form-group">
            <p>Status:

                <?php
                if($plan['ticket_status'] == 'complete') {
                    $trLabel = 'label-default';
                } else if($plan['ticket_status'] == 'progress') {
                    $trLabel = 'label-warning';
                } else {
                    $trLabel = 'label-success';
                }
                ?>

                <span class="label {!! $trLabel !!}">{!! strtoupper($plan['ticket_status']) !!}</span>
            </p>
            <p>Browser: {!! Html::image('images/' . $plan['browser'] . '.png', 'Browser', ['id' => 'browser-img']) !!}</p>
        </div>
    </div>
</div>