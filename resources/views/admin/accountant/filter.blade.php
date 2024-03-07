@extends('layouts.admin')

@section('content')
    {{-- @include('admin.include.filter-form') --}}
    @php
        // Define the year and month variables
        $year = 2024;
        $month = 2;
        // Calculate the start and end dates for the period you want to display
        $currentMonthStart = Carbon\Carbon::parse($year . '-' . $month . '-16'); // Start from the 16th of the current month
        $endOfMonth = $currentMonthStart->copy()->addMonth()->day(15); // End on the 15th of the next month

        // Fetch users from the database
        $users = App\Models\User::all(); // Assuming your user model is named 'User'
        $departments = App\Models\Department::all();
    @endphp

    <div class="row">
        <div class="col-md-6 mb-3 d-flex align-items-center">
            <form method="post" action="{{ route('admin.departure-records.filter') }}" class="d-flex">
                @csrf
                <select name="department_id" class="form-select form-select-sm me-2">
                    <option value="0">All</option>
                    {{-- Loop through each user to create an option --}}
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
                <select name="user_id" class="form-select form-select-sm me-2">
                    <option value="0">All</option>
                    {{-- Loop through each user to create an option --}}
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <input type="date" name="from" value="{{ old('from', $currentMonthStart->format('Y-m-d')) }}"
                    class="form-control form-control-sm me-2">
                <input type="date" name="to" value="{{ old('from', $endOfMonth->format('Y-m-d')) }}"
                    class="form-control form-control-sm me-2">
                <button class="btn btn-info btn-sm">検索</button>
            </form>
        </div>

    </div>

    <div class="col-md-10" style="overflow-x: auto;">
        <!-- Rest of your code -->
    </div>

    <!-- Button to submit the form without any specific filtering -->
    <div class="row mt-3">
        <div class="col-md-6">
            <form method="post" action="{{ route('admin.departure-records.filter') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Fetch All Data</button>
            </form>
        </div>
    </div>

    <div class="col-md-10" style="overflow-x: auto;">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-light table-sm table-smaller">
                <thead class="thead-success">
                    <tr>
                        <th>日付</th> <!-- Column header for date -->
                        <th>予定</th>
                        <th>勤怠区分</th>
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
                    @while ($currentMonthStart <= $endOfMonth)
                        @php
                            $currentMonthStart->setLocale('ja');
                        @endphp
                        <tr @if (in_array($currentMonthStart->dayOfWeek, [0, 6])) class="table-danger" @endif>
                            <td>
                                {{ $currentMonthStart->format('m') . '/' . $currentMonthStart->format('d') }}
                                ({{ $currentMonthStart->isoFormat('dd') }})
                            </td>
                            <td><!-- Data for 予定 column --></td>
                            <!-- Loop through arrival records to display arrival time -->
                            <td>
                                @foreach ($arrivals as $arrival)
                                    @php
                                        $arrivalDate = $arrival->recorded_at->format('Y-m-d');
                                        $arrivalTime = $arrival->recorded_at->format('H:i'); // Assuming 'recorded_at' contains the arrival time
                                    @endphp
                                    {{-- Debugging --}}
                                    <div>
                                        Arrival Date: {{ $arrivalDate }}
                                    </div>
                                    <div>
                                        Current Date: {{ $currentMonthStart->format('Y-m-d') }}
                                    </div>
                                    {{-- End of Debugging --}}
                                    @if ($arrivalDate == $currentMonthStart->format('Y-m-d'))
                                        {{ $arrivalTime }}
                                    @endif
                                @endforeach

                            </td>
                            <td><!-- Data for 勤怠区分 column --></td>
                            <td><!-- Data for 出勤時間 column --></td>
                            <td><!-- Data for 退勤時間 column --></td>
                            <td><!-- Data for 外出 column --></td>
                            <td><!-- Data for 労働時間 column --></td>
                            <td><!-- Data for 早出 column --></td>
                            <td><!-- Data for 定時超１ column --></td>
                            <td><!-- Data for 定時超２ column --></td>
                            <td><!-- Data for 遅刻 column --></td>
                            <td><!-- Data for 早退 column --></td>
                            <!-- Add more td elements as needed -->
                        </tr>
                        @php
                            $currentMonthStart->addDay(); // Move to the next day
                        @endphp
                    @endwhile
                </tbody>
            </table>
        </div>

        <table style="border-collapse: collapse; width: 100%;">
            <tr>
                <td>hello</td>
                <td>hello</td>
                <td>hello</td>
                <td>hello</td>
                <td>hello</td>
                <td>hello</td>
                <td>hello</td>
                <td>hello</td>
                <td>hello</td>
                <td style="border: 1px solid black; padding: 8px;">労働時間</td>
                <td style="border: 1px solid black; padding: 8px;">早出</td>
                <td style="border: 1px solid black; padding: 8px;">定時超１</td>
                <td style="border: 1px solid black; padding: 8px;">定時超２</td>
                <td style="border: 1px solid black; padding: 8px;">遅刻</td>
                <td style="border: 1px solid black; padding: 8px;">早退</td>
                <td style="border: 1px solid black; padding: 8px;">休出</td>
                <td style="border: 1px solid black; padding: 8px;">平日</td>
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
            </tr>
            <div class="col-md-2 d-flex align-items-center justify-content-end">
                <button class="btn btn-success btn-sm w-100">CSV</button>
            </div>
            <br>
        </table>

    </div>
@endsection
