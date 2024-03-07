@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.create') }} {{ trans('cruds.departureRecord.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.departure-records.store") }}" enctype="multipart/form-data">
                        @method('POST')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="arrival_id">{{ trans('cruds.departureRecord.fields.arrival') }}</label>
                            <select class="form-control select2" name="arrival_id" id="arrival_id" required>
                                @foreach($arrivals as $id => $entry)
                                    <option value="{{ $id }}" {{ old('arrival_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('arrival'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('arrival') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.departureRecord.fields.arrival_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label class="required" for="recorded_at">{{ trans('cruds.departureRecord.fields.recorded_at') }}</label>
                            <input class="form-control datetime" type="text" name="recorded_at" id="recorded_at" value="{{ old('recorded_at') }}" required>
                            @if($errors->has('recorded_at'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('recorded_at') }}
                                </div>
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

        </div>
    </div>
</div>
@endsection