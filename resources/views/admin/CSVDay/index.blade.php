@extends('layouts.admin')

@section('content')

    @php

        $departments = App\Models\Department::all();
        $users = App\Models\User::all();
        $selectedDepartmentId = request()->input('department_id', 0);
        $selectedUserId = request()->input('user_id', 0);

    @endphp

    <div class="row">
        <div class="col-md-6 mb-3 d-flex align-items-center">
            <form method="get" action="{{ route('admin.CSVDay.filter') }}" class="d-flex">
                <select name="department_id" class="form-select form-select-sm me-2" id="department">
                    <option value="0" @if ($selectedDepartmentId == 0) selected @endif>All</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" @if ($selectedDepartmentId == $department->id) selected @endif>
                            {{ $department->name }}</option>
                    @endforeach
                </select>
                <select name="user_id" class="form-select form-select-sm me-2" id="user_select">
                    <option value=0 @if ($selectedUserId == 0) selected @endif>All</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" @if ($selectedUserId == $user->id) selected @endif>
                            {{ $user->name }}</option>
                    @endforeach
                </select>
                <select name="from_month" class="form-control form-control-sm me-2">
                    @for ($i = 1; $i < 13; $i++)
                        <option value="{{ $i }}" {{ old('from_month') == $i ? 'selected' : '' }}>
                            {{ $i }}月</option>
                    @endfor
                </select>
                <button id="button" class="btn btn-info btn-sm">検索</button>
            </form>
        </div>
    </div>

    <?php
    if ($selectedUserId == 0) {
        // Fetch arrival time and departure time for all users
        $arrivalTimes = User::pluck('arrival_time');
        $departureTimes = User::pluck('departure_time');
        // Use $arrivalTimes and $departureTimes as needed
    } else {
        // Fetch arrival time and departure time for the selected user
        $user = User::find($selectedUserId);
        $arrivalTime = $user->arrival_time;
        $departureTime = $user->departure_time;
        // Use $arrivalTime and $departureTime as needed
    }
    ?>

    @if (isset($arrivals))
        <!--end nohtsol shalgaad baina-->
        @php
            if ($selectedUserId == 0) {
                $filteredUsers = $users;
            } else {
                $filteredUsers = $users->where('id', $selectedUserId);
            }
        @endphp
        @foreach ($filteredUsers as $user)
            <div class="col-md-10" style="overflow-x: auto;">
                <div class="table-responsive">
                    <style>
                        .table th,
                        .table td {
                            font-size: 0.95rem;
                            padding: 0.6rem;
                            text-align: center;
                            vertical-align: middle;
                        }
                    </style>

                    <table class="table table-bordered table-hover table-light table-sm table-smaller">
                        <thead class="thead-success">
                            <tr>
                                <th>日付</th>
                                <th>出勤区別</th>
                                <th>社員番号</th>
                                <th>氏名</th>
                                <th>出勤時間</th>
                                <th>退勤時間</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $startDate = Carbon\Carbon::parse($startdate);
                                $endDate = Carbon\Carbon::parse($enddate);
                            @endphp

                            @for ($date = $startDate; $date->lte($endDate); $date->addDay())
                                @php
                                    $currentDate = $date;
                                    $currentDate->setLocale('ja');
                                    $filterDate = $date->copy();
                                    $arrivalsFromDate = $arrivals->filter(function ($arrival) use ($filterDate, $user) {
                                        return explode(' ', $arrival->recorded_at)[0] ===
                                            $filterDate->format('Y-m-d') && $arrival->user_id == $user->id;
                                    });
                                    $arrival = $arrivalsFromDate->first();
                                    $arrivalTime = $arrival
                                        ? Carbon\Carbon::parse($arrival->recorded_at)->format('H:i')
                                        : '';
                                    $departureTime = $arrival
                                        ? Carbon\Carbon::parse(
                                            $arrival->DepartureRecord ? $arrival->DepartureRecord->recorded_at : '',
                                        )->format('H:i')
                                        : '';

                                @endphp
                                <tr @if (in_array($currentDate->dayOfWeek, [0, 6])) class="table-danger" @endif>
                                    <td>{{ $currentDate->format('m/d') }} ({{ $currentDate->isoFormat('dd') }})</td>
                                    <td>
                                        <select name="amraltiin_turul" class="form-select form-select-sm">
                                            @php
                                                $dayOfWeek = $currentDate->dayOfWeek;
                                                $isWorkDay = $dayOfWeek >= 1 && $dayOfWeek <= 5;
                                            @endphp
                                            <option value="work_day" {{ $isWorkDay ? 'selected' : '' }}>出勤日</option>
                                            <option value="holiday" {{ !$isWorkDay ? 'selected' : '' }}>公休</option>
                                            <option value="holiday1">有休</option>
                                            <option value="holiday2">半休</option>
                                            <option value="holiday3">振休</option>
                                            <option value="holiday4">特休</option>
                                            <option value="holiday5">欠勤</option>
                                            <option value="holiday6">産休</option>
                                            <option value="holiday7">育休</option>
                                        </select>
                                    </td>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $arrivalTime }}</td>
                                    <td>{{ $departureTime }}</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>

                </div>

            </div>
        @endforeach
    @endif

    <div>
        <button onclick="exportToCSV()" class="btn btn-info">CSVとしてダウンロード</button>
        <br><br>
    </div>
    <script>
        function exportToCSV() {
            // Get table data
            var tables = document.querySelectorAll('.table');

            // Prepare CSV content
            var csvContent = [];

            // Encode header row
            var headerRow = [];
            tables[0].querySelector('thead').querySelectorAll('th').forEach(function(cell) {
                headerRow.push('"' + cell.innerText.replace(/"/g, '""') + '"');
            });
            csvContent.push(headerRow.join(','));

            // Encode data rows for each user
            tables.forEach(function(table) {
                var rows = table.querySelectorAll('tbody tr');
                rows.forEach(function(row) {
                    var rowData = [];
                    row.querySelectorAll('td').forEach(function(cell) {
                        if (cell.querySelector('select')) {
                            // If the cell contains a select dropdown
                            var selectedOption = cell.querySelector('select option:checked')
                                .innerText;
                            rowData.push('"' + selectedOption.replace(/"/g, '""') + '"');
                        } else {
                            rowData.push('"' + cell.innerText.replace(/"/g, '""') + '"');
                        }
                    });
                    csvContent.push(rowData.join(','));
                });
            });

            // Convert to Blob with UTF-8 encoding
            var csvString = csvContent.join('\n');
            var csvData = new Blob(["\uFEFF" + csvString], { // Prepend BOM character for proper Excel handling
                type: 'text/csv;charset=utf-8;'
            });

            // Create download link
            var link = document.createElement('a');
            link.href = window.URL.createObjectURL(csvData);
            link.setAttribute('download', 'table_data.csv');

            // Append link to DOM and trigger download
            document.body.appendChild(link);
            link.click();
        }
    </script>

@endsection
