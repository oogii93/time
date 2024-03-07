@extends('layouts.admin')

@section('content')
    {{-- @include('admin.include.filter-form') --}}
    @php

        $departments = App\Models\Department::all();
    @endphp

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
                            <th>予定</th>

                            <th>出勤時間</th>
                            <th>退勤時間</th>
                            <th>外出</th>
                            <th>労働時間</th>
                            <th>早出</th>
                            <th>定時超１</th>
                            <th>定時超２</th>
                            <th>遅刻</th>
                            <th>早退</th>
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

                                    // Format the time difference as per your requirement
                                    // dd($timeDifference);
                                    // dd($formattedDifference);

                                    // Add the departure time to include the last hour

                                    // Now $hours array contains all the hours between arrival and departure
                                }

                                // Calculate the difference in hours

                                //     // Define time ranges
                                //     $timeRange1Start = \Carbon\Carbon::parse('08:30');
                                //     $timeRange1End = \Carbon\Carbon::parse('12:00');
                                //     $timeRange2Start = \Carbon\Carbon::parse('13:00');
                                //     $timeRange2End = \Carbon\Carbon::parse('17:30');
                                //     if ($departureDateTime->greaterThan($timeRange2End)) {
                                //         $departureDateTime = $timeRange2End;
                                //     }

                                //     // Calculate the difference in hours between arrival and departure times
                                //     $hoursBetween = $arrivalDateTime->diffInHours($departureDateTime);

                                //     // Initialize counters for each time range
                                //     $countInRange1 = 0;
                                //     $countInRange2 = 0;

                                //     // Iterate over each hour in the range and count full hours
                                //     for ($i = 0; $i < $hoursBetween; $i++) {
                                //         $currentHour = $arrivalDateTime->copy()->addHours($i);

                                //         // Check if the current hour falls within time range 1
                                //         if ($currentHour->between($timeRange1Start, $timeRange1End)) {
                                //             $countInRange1++;
                                //         }
                                //         // Check if the current hour falls within time range 2
                                //         elseif ($currentHour->between($timeRange2Start, $timeRange2End)) {
                                //             $countInRange2++;
                                //         }
                                //     }
                                //     // Calculate the total minutes between arrival and departure times
                                //     $totalMinutes = $hoursBetween * 60;

                                //     // Calculate the adjusted minutes by subtracting 10 minutes for each counted hour within the time ranges
                                //     $adjustedMinutes = $countInRange1 * 10 + $countInRange2 * 10;

                                //     // Calculate the worked minutes by subtracting the adjusted minutes from the total minutes
                                //     $workedMinutes = $totalMinutes - $adjustedMinutes;

                                //     // Convert worked minutes to hours and minutes in H:i format
                                //     $workedHours = floor($workedMinutes / 60); // Extract the hours
                                //     $workedMinutes %= 60; // Extract the remaining minutes

                                //     // Format the worked hours and minutes as H:i
                                //     $workedHoursFormatted = sprintf('%02d:%02d', $workedHours, $workedMinutes);

                                //     //niit ajilsan tsag

                                //     // Now $countInRange1 contains the count of full hours within time range 1
                                //     // And $countInRange2 contains the count of full hours within time range 2
                                // } else {
                                //     $workedHoursFormatted = '';
                                // }

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

                                <td></td>
                                {{-- <td>{{ $formattedDifference }}</td> --}}
                                {{-- <td>{{ $formattedDifference }}</td> --}}
                                <td><!-- Data for 労働時間 column --></td>
                                <td><!-- Data for 早出 column --></td>
                                <td><!-- Data for 定時超１ column --></td>
                                <td><!-- Data for 定時超２ column --></td>
                                <td><!-- Data for 遅刻 column --></td>
                                <td><!-- Data for 早退 column --></td>
                                <!-- Add more td elements as needed -->
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>

            <table style="border-collapse: collapse; width: 100%;">
                <tr>
                    <th style="border: 1px solid black; padding: 8px;">名前</th>
                    <th style="border: 1px solid black; padding: 8px;">平日</th>
                    <th style="border: 1px solid black; padding: 8px;">休出</th>
                    <th style="border: 1px solid black; padding: 8px;">労働時間</th>
                    <th style="border: 1px solid black; padding: 8px;">遅刻</th>
                    <th style="border: 1px solid black; padding: 8px;">早退</th>
                    <th style="border: 1px solid black; padding: 8px;">有休</th>
                    <th style="border: 1px solid black; padding: 8px;">代休</th>
                    <th style="border: 1px solid black; padding: 8px;">公休</th>

                    <th style="border: 1px solid black; padding: 8px;">振休</th>
                    <th style="border: 1px solid black; padding: 8px;">特休</th>
                    <th style="border: 1px solid black; padding: 8px;">欠勤</th>
                    <th style="border: 1px solid black; padding: 8px;">産休</th>
                    <th style="border: 1px solid black; padding: 8px;">育休</th>

                    <th style="border: 1px solid black; padding: 8px;">早出</th>
                    <th style="border: 1px solid black; padding: 8px;">定時超１</th>
                    <th style="border: 1px solid black; padding: 8px;">定時超２</th>
                    <th style="border: 1px solid black; padding: 8px;">外出</th>


                </tr>
                <tr>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>
                    <td style="border: 1px solid black; padding: 8px;"></td>

                </tr>



                <div class="col-md-2 d-flex align-items-center justify-content-end">
                    <a href="" class="btn btn-success">CSV</a>
                </div>

                <br>
            </table>



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
