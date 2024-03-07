@extends('layouts.admin')

@section('content')
    @can('departure_record_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.departure-records.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.departureRecord.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    @include('admin.include.filter-form')
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.departureRecord.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-DepartureRecord">
                <thead>
                    <tr>
                        <th>&nbsp;</th>
                        <th>社員番号</th>
                        <th>所属</th>
                        <th>名前</th>
                        <th>出勤時間</th>
                        <th>退勤時間</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $departure)
                        <tr>
                            <td>&nbsp;</td>
                            <td>{{ $departure->id }}</td>
                            <td>
                                @php
                                    $department = 'no department';
                                    if ($departure->arrival && $departure->arrival->user) {
                                        $user = $departure->arrival->user;
                                        $department = $user->staffsDepartments->count()
                                            ? implode(', ', $user->staffsDepartments->pluck('name')->toArray())
                                            : 'no department';
                                    }
                                    echo $department;
                                @endphp
                            </td>
                            <td>{{ $departure->arrival ? $departure->arrival->user->name : '' }}</td>
                            <td>{{ optional($departure->arrival)->recorded_at }}</td>
                            <td>{{ $departure->recorded_at }}</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('.datatable-DepartureRecord').DataTable();

            // Get the sorted order from the server-side data
            var sortedIds = {!! json_encode($results->pluck('id')->toArray()) !!};

            // Reorder table rows based on sorted IDs
            for (var i = 0; i < sortedIds.length; i++) {
                var row = table.row('#row_' + sortedIds[i]);
                row.remove().draw(false);
                table.row.add(row.node()).draw(false);
            }
        });
    </script>
@endsection
