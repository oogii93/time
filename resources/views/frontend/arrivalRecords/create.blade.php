@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.arrivalRecord.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.arrival-records.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="user_id">{{ trans('cruds.arrivalRecord.fields.user') }}</label>
                            <select class="form-control select2" name="user_id" id="user_id" required>
                                @foreach($users as $id => $entry)
                                    <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.arrivalRecord.fields.user_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="recorded_at">{{ trans('cruds.arrivalRecord.fields.recorded_at') }}</label>
                            <input class="form-control datetime" type="text" name="recorded_at" id="recorded_at" value="{{ old('recorded_at') }}" required>
                            @if($errors->has('recorded_at'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('recorded_at') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection