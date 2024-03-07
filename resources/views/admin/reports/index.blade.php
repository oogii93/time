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
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.departureRecord.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <form id="dateRangeForm">
                <div class="form-group">
                    <label for="from_date">いつから：</label>
                    <input type="date" id="from_date" name="from_date" class="form-control">
                </div>
                <div class="form-group">
                    <label for="to_date">いつまで:</label>
                    <input type="date" id="to_date" name="to_date" class="form-control">
                </div>
                <button type="submit" class="btn btn-primary">検索</button>
            </form>


            <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-DepartureRecord">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.departureRecord.fields.id') }}
                        </th>
                        <th>
                            名前
                        </th>
                        <th>
                            出勤時間
                        </th>
                        <th>
                            退勤時間
                        </th>

                        <th>
                            &nbsp;
                        </th>

                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
    @parent
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            @can('departure_record_delete')
                let deleteButtonTrans = '{{ trans('global.datatables.delete') }}';
                let deleteButton = {
                    text: deleteButtonTrans,
                    url: "{{ route('admin.departure-records.massDestroy') }}",
                    className: 'btn-danger',
                    action: function(e, dt, node, config) {
                        var ids = $.map(dt.rows({
                            selected: true
                        }).data(), function(entry) {
                            return entry.id
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}')

                            return
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            $.ajax({
                                    headers: {
                                        'x-csrf-token': _token
                                    },
                                    method: 'POST',
                                    url: config.url,
                                    data: {
                                        ids: ids,
                                        _method: 'DELETE'
                                    }
                                })
                                .done(function() {
                                    location.reload()
                                })
                        }
                    }
                }
                dtButtons.push(deleteButton)
            @endcan

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: {
                    url: "{{ route('admin.report') }}",
                    type: "GET", // or "GET" depending on your server-side route configuration
                    data: function(d) {
                        d.from_date = $('#from_date').val();
                        // assuming you have inputs with id 'from_date' and 'to_date'
                        d.to_date = $('#to_date').val();
                        // You can add more custom data here if needed
                    },


                },
                columns: [{
                        data: 'placeholder',
                        name: 'placeholder'
                    },
                    {
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'arrival.user.name'
                    },
                    {
                        data: 'arrival_recorded_at',
                        name: 'arrival.recorded_at'
                    },
                    {
                        data: 'recorded_at',
                        name: 'recorded_at'
                    },
                    {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    }
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100,
            };
            let table = $('.datatable-DepartureRecord').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });

        });
    </script>
@endsection
