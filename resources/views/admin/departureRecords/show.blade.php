@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.departureRecord.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.departure-records.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.departureRecord.fields.id') }}
                        </th>
                        <td>
                            {{ $departureRecord->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.departureRecord.fields.arrival') }}
                        </th>
                        <td>
                            {{ $departureRecord->arrival->recorded_at ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.departureRecord.fields.recorded_at') }}
                        </th>
                        <td>
                            {{ $departureRecord->recorded_at }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.departure-records.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection