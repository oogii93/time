@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.departureRecord.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.departure-records.update", [$departureRecord->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="arrival_id">{{ trans('cruds.departureRecord.fields.arrival') }}</label>
                <select class="form-control select2 {{ $errors->has('arrival') ? 'is-invalid' : '' }}" name="arrival_id" id="arrival_id" required>
                    @foreach($arrivals as $id => $entry)
                        <option value="{{ $id }}" {{ (old('arrival_id') ? old('arrival_id') : $departureRecord->arrival->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('arrival'))
                    <span class="text-danger">{{ $errors->first('arrival') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.departureRecord.fields.arrival_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="recorded_at">{{ trans('cruds.departureRecord.fields.recorded_at') }}</label>
                <input class="form-control datetime {{ $errors->has('recorded_at') ? 'is-invalid' : '' }}" type="text" name="recorded_at" id="recorded_at" value="{{ old('recorded_at', $departureRecord->recorded_at) }}" required>
                @if($errors->has('recorded_at'))
                    <span class="text-danger">{{ $errors->first('recorded_at') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.departureRecord.fields.recorded_at_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection