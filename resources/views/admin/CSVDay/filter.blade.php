@extends('layouts.admin')

@section('content')
    {{-- @include('admin.include.filter-form') --}}
    @php

        $departments = App\Models\Department::all();
    @endphp
    {{--
    <div class="row">
        <div class="col-md-6 mb-3 d-flex align-items-center">
            <form method="get" action="{{ route('admin.accountant.filter') }}" class="d-flex">

                <select name="department_id" class="form-select form-select-sm me-2" id="department">
                    <option selected disabled>選択</option>
                    <option value="0">All</option>
                    {{-- Loop through each user to create an option --}}
    @foreach ($departments as $department)
        <option value="{{ $department->id }}">{{ $department->name }}</option>
    @endforeach
    </select>
    <select name="user_id" class="form-select form-select-sm me-2" id="user_select">

        {{-- Loop through each user to create an option --}}

    </select>
    <select name="from_month" class="form-control form-control-sm me-2">
        @for ($i = 1; $i < 13; $i++)
            <option value="{{ $i }}" {{ old('from_month') == $i ? 'selected' : '' }}>
                {{ $i }}月</option>
        @endfor
    </select>
    <button id= "button" disabled class="btn btn-info btn-sm">検索</button>
    </form>


    </div>

    </div> --}}

    <div class="row">
        <div class="col-md-6 mb-3 d-flex align-items-center">
            <form method="get" action="{{ route('admin.accountant.filter') }}" class="d-flex">
                <select name="department_id" class="form-select form-select-sm me-2" id="department">
                    <option selected disabled>選択</option>
                    <option value="0">All</option>
                    {{-- Loop through each department to create an option --}}
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>

                <!-- Remove the user select element -->

                <select name="from_month" class="form-control form-control-sm me-2">
                    @for ($i = 1; $i < 13; $i++)
                        <option value="{{ $i }}" {{ old('from_month') == $i ? 'selected' : '' }}>
                            {{ $i }}月</option>
                    @endfor
                </select>
                <button id="button" disabled class="btn btn-info btn-sm">検索</button>
            </form>
        </div>
    </div>


    <div class="col-md-10" style="overflow-x: auto;">

    </div>




    @if (isset($arrivals))
        <div class="col-md-10" style="overflow-x: auto;">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-light table-sm table-smaller">
                    <thead class="thead-success">
                        <tr>
                            <th>日付</th> <!-- Column header for date -->
                            <th>出勤区別</th>
                            <th>社員番号</th>
                            <th>氏名</th>
                            <th>出勤時間</th>
                            <th>退勤時間</th>

                            <!-- Add more table headers as needed -->
                        </tr>
                    </thead>
                    <tbody>

                        @php
                            $startDate = Carbon\Carbon::parse($startdate);
                            $endDate = Carbon\Carbon::parse($enddate);

                        @endphp

                        @for ($date = $startDate; $date->lte($endDate); $date->addDay())
                            @php
                                //formataa odoroo songood array dotroosoo arrival time olood departure duudna
                                //arrival ni useree duudnaa,
                                //back-aas irsen saraa dawtana
                                //compactaar ilgeeh
                                $currentDate = $date;

                                $currentDate->setLocale('ja');

                                $filterDate = $date->copy();
                                // Retrieve data from $arrivals for the current date in the loop
                                $arrivalsFromDate = $arrivals->filter(function ($arrival) use ($filterDate) {
                                    return explode(' ', $arrival->recorded_at)[0] === $filterDate->format('Y-m-d');
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
                                if ($arrivalTime && $departureTime) {
                                    $arrivalDateTime = \Carbon\Carbon::parse($arrivalTime);
                                    $departureDateTime = \Carbon\Carbon::parse($departureTime);

                                    $timeDifference = $arrivalDateTime->diff($departureDateTime);
                                }
                            @endphp
                            <tr @if (in_array($currentDate->dayOfWeek, [0, 6])) class="table-danger" @endif>
                                <td>
                                    {{ $currentDate->format('m') . '/' . $currentDate->format('d') }}
                                    ({{ $currentDate->isoFormat('dd') }})
                                </td>
                                <td><!-- Data for 予定 column --></td>

                                <td>
                                    {{ $arrivalTime }}

                                </td>
                                <td>
                                    {{ $departureTime }}

                                </td>


                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>



        </div>
    @endif
@endsection

@section('scripts')
    <script>
        $('#department').on('change', function() {
            let _token = $('meta[name="csrf-token"]').attr('content');
            let id = $(this).val();
            $.ajax({
                url: '{{ route('admin.accountant.getDepartment') }}',
                type: "post",
                data: {
                    id: id,
                    _method: 'patch',
                    _token: _token
                },

                success: function(data) {
                    console.log(data);
                    $('#user_select').html(data);
                    $('#button').prop('disabled', false);



                },
            });
        });
    </script>
@endsection
