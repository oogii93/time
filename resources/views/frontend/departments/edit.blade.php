@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.department.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.departments.update", [$department->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.department.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $department->name) }}" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.department.fields.name_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="staffs">{{ trans('cruds.department.fields.staffs') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2" name="staffs[]" id="staffs" multiple>
                                @foreach($staffs as $id => $staff)
                                    <option value="{{ $id }}" {{ (in_array($id, old('staffs', [])) || $department->staffs->contains($id)) ? 'selected' : '' }}>{{ $staff }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('staffs'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('staffs') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.department.fields.staffs_helper') }}</span>
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