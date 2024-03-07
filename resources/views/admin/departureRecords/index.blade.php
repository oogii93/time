@extends('layouts.admin')

@section('content')
    @can('departure_record_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a class="btn btn-success" href="{{ route('admin.departure-records.create') }}">
                    退勤追加
                </a>
            </div>
        </div>
    @endcan
    @include('admin.include.filter-form')
    <div class="card">
        <div class="card-header">
            出退勤レポート
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped table-hover ajaxTable datatable datatable-DepartureRecord">
                <thead>
                    <tr>
                        <th>check</th>
                        <th>date</th>

                        <th>社員番号</th>
                        <th>所属</th>
                        <th>名前</th>
                        <th>出勤時間</th>
                        <th>退勤時間</th>
                        <th>&nbsp;</th>
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
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
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
                            return entry.id;
                        });

                        if (ids.length === 0) {
                            alert('{{ trans('global.datatables.zero_selected') }}');
                            return;
                        }

                        if (confirm('{{ trans('global.areYouSure') }}')) {
                            let _token = $('meta[name="csrf-token"]').attr('content');
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
                            }).done(function() {
                                location.reload();
                            });
                        }
                    }
                };
                dtButtons.push(deleteButton);
            @endcan

            let dtOverrideGlobals = {
                buttons: dtButtons,
                processing: true,
                serverSide: true,
                retrieve: true,
                aaSorting: [],
                ajax: "{{ route('admin.departure-records.index') }}",
                columns: [{
                        data: 'date',
                        name: 'check'
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

                    // Additional rows
                    {
                        data: 'additional_data_1', // Replace with actual data property
                        name: 'additional_data_1'
                    },
                    {
                        data: 'additional_data_2', // Replace with actual data property
                        name: 'additional_data_2'
                    } {
                        data: 'actions',
                        name: '{{ trans('global.actions') }}'
                    },
                ],
                orderCellsTop: true,
                order: [
                    [1, 'desc']
                ],
                pageLength: 100
            };

            let table = $('.datatable-DepartureRecord').DataTable(dtOverrideGlobals);
            $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e) {
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            });
        });
    </script>
@endsection
