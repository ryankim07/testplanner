{{--
|--------------------------------------------------------------------------
| Admin results search form partial
|--------------------------------------------------------------------------
|
| This partial is used when showing search form on list results.
|
--}}

@if(isset($columns) && count($columns) > 0)
    <colgroup>
        @foreach($columns as $column)
            <col width="{!! $column['width'] !!}">
        @endforeach
    </colgroup>
    <thead>
    <tr>
        @foreach($columns as $column)
            <th>
                @if ($column['sortable'])
                    {!! Html::linkAction($columnsLink, $column['colname'], [
                        'sortBy' => $column['sortable'],
                        'order'  => $column['order'] == 'ASC' ? 'DESC' : 'ASC'
                    ]) !!}
                @else
                    {!! $column['colname'] !!}
                @endif
            </th>
        @endforeach
    </tr>

    <!-- FILTERS -->

    <tr class="column-filters">
        @foreach($columns as $column)
            <th>
                @if ($column['type'] == 'text' && $column['filterable'])
                    <div class="form-group col-md-12">
                        {!! Form::text($column['filters']['attr']['index'], null, $column['filters']['attr']['data']) !!}
                    </div>
                @elseif ($column['type'] == 'date' && $column['filterable'])
                    @foreach($column['filters'] as $filter)
                        <div class="form-group col-md-12">
                            {!! Form::label($filter['attr']['index'], $filter['attr']['label'], ['class' => 'label-control col-md-4']) !!}
                            <div class='input-group date col-md-8' id='{!! $filter['attr']['index'] !!}'>
                                {!! Form::text($filter['attr']['index'], null, $filter['attr']['data']) !!}
                                <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                            </div>
                        </div>
                    @endforeach
                @elseif ($column['type'] == 'select')
                    <div class="form-group col-md-12">
                        {!! Form::text($column['filters']['attr']['index'], null, $column['filters']['attr']['data']) !!}
                    </div>
                @else
                    <div class="form-group col-md-12"></div>
                @endif
            </th>
        @endforeach
    </tr>
    </thead>
@endif