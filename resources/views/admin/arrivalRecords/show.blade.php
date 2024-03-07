@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.arrivalRecord.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.arrival-records.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.arrivalRecord.fields.id') }}
                        </th>
                        <td>
                            {{ $arrivalRecord->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.arrivalRecord.fields.user') }}
                        </th>
                        <td>
                            {{ $arrivalRecord->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.arrivalRecord.fields.recorded_at') }}
                        </th>
                        <td>
                            {{ $arrivalRecord->recorded_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.arrival-records.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#arrival_departure_records" role="tab" data-toggle="tab">
                {{ trans('cruds.departureRecord.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="arrival_departure_records">
            @includeIf('admin.arrivalRecords.relationships.arrivalDepartureRecords', ['departureRecords' => $arrivalRecord->arrivalDepartureRecords])
        </div>
    </div>
</div>

@endsection