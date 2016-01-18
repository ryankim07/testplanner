{{--
|--------------------------------------------------------------------------
| Response tickets partial
|--------------------------------------------------------------------------
|
| This template is used when rendering tickets.
|
--}}

    @foreach($plan['tickets'] as $ticket)
        <div class="page-header"></div>
        <div class="row nested-block ticket-panel">
            <legend>Ticket - {!! Html::link(isset($ticket['description_url']) ? $ticket['description_url'] : '#', $ticket['desc'], ['class' => 'jira-issue', 'target' => '_blank', 'title' => 'Click to view issue in Jira']) !!}</legend>
            <div class="col-xs-12 col-md-6">
                <div class="form-group">
                    <legend>Objective</legend>
                    <p><span>{!! $ticket['objective'] !!}</span></p>
                </div>
                <div class="form-group">
                    <legend>Steps to test</legend>
                    <p><span>{!! nl2br($ticket['test_steps']) !!}</span></p>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <legend>Status</legend>
                @if($mode == 'respond')
                    <div class="radio">
                        <?php
                        $passed = '';
                        $failed = '';

                        if (isset($ticket['test_status'])) {
                            $passed = $ticket['test_status'] == 1 ? true : '';
                            $failed = $ticket['test_status'] == 0 ? true : '';
                        }
                        ?>

                        <label>
                            {!! Form::radio('test_status[' . $ticket["id"] . ']', 1, $passed, ['class' => 'test_status']) !!}
                            Passed
                        </label>
                        <label>
                            {!! Form::radio('test_status[' . $ticket["id"] . ']', 0, $failed, ['class' => 'test_status']) !!}
                            Failed
                        </label>

                    </div>
                @else
                    <?php
                        $passed = '';
                        $failed = '';
                        if (isset($ticket['test_status'])) {
                            $passed = $ticket['test_status'] == 1 ? true : '';
                            $failed = $ticket['test_status'] == 0 ? true : '';
                        }
                    ?>

                    <p>
                        <span>
                        @if($passed)
                            Passed
                        @elseif($failed)
                            Failed
                        @endif
                        </span>
                    </p>
                @endif
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="form-group">
                    <legend>Notes</legend>
                    @if($mode == 'respond')
                        <?php $notesResponse = isset($ticket['notes_response']) ? $ticket['notes_response'] : null; ?>

                        {!! Form::textarea('notes_response', $notesResponse, ['class' => 'form-control notes-response', 'rows' => '10']) !!}
                    @else
                        {!! isset($ticket['notes_response']) ? nl2br($ticket['notes_response']) : null !!}
                    @endif
                </div>
            </div>
            @if($mode == 'respond')
                {!! Form::hidden('ticket_id', $ticket['id'], ['class' => 'ticket-id']) !!}
            @endif
        </div>
    @endforeach