@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.arrivalRecord.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.arrival-records.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.arrivalRecord.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <span class="text-danger">{{ $errors->first('user') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.arrivalRecord.fields.user_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="recorded_at">{{ trans('cruds.arrivalRecord.fields.recorded_at') }}</label>
                <input class="form-control datetime {{ $errors->has('recorded_at') ? 'is-invalid' : '' }}" type="text" name="recorded_at" id="recorded_at" value="{{ old('recorded_at') }}" required>
                @if($errors->has('recorded_at'))
                    <span class="text-danger">{{ $errors->first('recorded_at') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.arrivalRecord.fields.recorded_at_helper') }}</span>
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