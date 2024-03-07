@extends('layouts.admin')
@section('content')
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.user.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.users.index') }}">
                        {{ trans('global.back_to_list') }}
                    </a>
                </div>
                <table class="table table-bordered table-striped">
                    <tbody>
                        <tr>
                            <th>
                                id
                            </th>
                            <td>
                                {{ $user->id }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('staff_number') }}
                            </th>
                            <td>
                                {{ $user->staff_number }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <td>
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <td>
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <td>
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <td>
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <td>
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <td>
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <td>
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <td>
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <td>
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.name') }}
                            </th>
                            <td>
                                {{ $user->name }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.email') }}
                            </th>
                            <td>
                                {{ $user->email }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.phone') }}
                            </th>
                            <td>
                                {{ $user->phone }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.address') }}
                            </th>
                            <td>
                                {{ $user->address }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.email_verified_at') }}
                            </th>
                            <td>
                                {{ $user->email_verified_at }}
                            </td>
                        </tr>
                        <tr>
                            <th>
                                {{ trans('cruds.user.fields.roles') }}
                            </th>
                            <td>
                                @foreach ($user->roles as $key => $roles)
                                    <span class="label label-info">{{ $roles->title }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-group">
                    <a class="btn btn-default" href="{{ route('admin.users.index') }}">
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
                <a class="nav-link" href="#user_arrival_records" role="tab" data-toggle="tab">
                    {{ trans('cruds.arrivalRecord.title') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#staffs_departments" role="tab" data-toggle="tab">
                    {{ trans('cruds.department.title') }}
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane" role="tabpanel" id="user_arrival_records">
                @includeIf('admin.users.relationships.userArrivalRecords', [
                    'arrivalRecords' => $user->userArrivalRecords,
                ])
            </div>
            <div class="tab-pane" role="tabpanel" id="staffs_departments">
                @includeIf('admin.users.relationships.staffsDepartments', [
                    'departments' => $user->staffsDepartments,
                ])
            </div>
        </div>
    </div>
@endsection
