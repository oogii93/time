<!-- Loop through each day of the previous month -->
@php
    $previousMonthStart = Carbon\Carbon::parse($year . '-' . $month . '-16')->subMonth();
    $currentMonthStart = Carbon\Carbon::parse($year . '-' . $month . '-16');
    $daysInPreviousMonth = $previousMonthStart->daysInMonth;
    $totalMinutesForMonth = 0; // Initialize total hours for the month
@endphp

@for ($i = 0; $i < $daysInPreviousMonth; $i++)
    @php
        $day = $previousMonthStart->copy()->addDays($i);
        $day->setLocale('ja');

        $startOfDay = $day->startOfDay()->format('Y-m-d H:i:s');
        $endOfDay = $day->endOfDay()->format('Y-m-d H:i:s');
    @endphp

    {{-- Your code here --}}

    <tr @if (in_array($day->dayOfWeek, [0, 6])) class="table-danger" @endif>
        <td>
            {{ $day->format('m') . '/' . $day->format('d') }}
            ({{ $day->isoFormat('dd') }})
        </td>
        <td>
            <!-- Display arrival time for the day -->
            @php
                $arrival = $user
                    ->userArrivalRecords()
                    ->whereBetween('recorded_at', [$startOfDay, $endOfDay])
                    ->first();
                $arrivalTime = $arrival ? Carbon\Carbon::parse($arrival->recorded_at) : null;
                $departureTime =
                    $arrival && $arrival->arrivalDepartureRecords->count()
                        ? Carbon\Carbon::parse($arrival->arrivalDepartureRecords->first()->recorded_at)
                        : null;
                if ($arrivalTime && $departureTime) {
                    $result = workTimeCalc($arrivalTime->format('H:i'), $departureTime->format('H:i'));
                } else {
                    $result = null;
                }

                echo $arrival ? \Carbon\Carbon::parse($arrival->recorded_at)->format('H:i') : '';
            @endphp
        </td>
        <td>
            <!-- Display departure time for the day -->
            @if ($arrival && $arrival->arrivalDepartureRecords->count() > 0)
                {{ \Carbon\Carbon::parse($arrival->arrivalDepartureRecords->first()->recorded_at)->format('H:i') }}
            @endif
        </td>
        <td>
            <!-- Calculate and display total hours worked for the day -->
            @php
                if ($result) {
                    $arrayWorkedMinutes = explode(':', $result['workedTime']);
                    $totalMinutesForMonth += $arrayWorkedMinutes[0] * 60 + $arrayWorkedMinutes[1];

                    // Format the time as H:i
                    echo sprintf('%02d:%02d', $arrayWorkedMinutes[0], $arrayWorkedMinutes[1]);
                } else {
                    echo '';
                }
            @endphp
        </td>

        <td>
            <!-- Calculate and display total hours overtime1 for the day -->
            @php
                if ($result) {
                    $arrayOverTime1 = explode(':', $result['overTime1']);
                    $totalMinutesForMonth += $arrayOverTime1[0] * 60 + $arrayOverTime1[1];

                    // Format the time as H:i
                    echo sprintf('%02d:%02d', $arrayOverTime1[0], $arrayOverTime1[1]);
                } else {
                    echo '';
                }
            @endphp
        </td>
        <td>
            <!-- Calculate and display total hours overtime2 for the day -->
            @php
                if ($result) {
                    $arrayOverTime2 = explode(':', $result['overTime2']);
                    $totalMinutesForMonth += $arrayOverTime2[0] * 60 + $arrayOverTime2[1];

                    // Format the time as H:i
                    echo sprintf('%02d:%02d', $arrayOverTime2[0], $arrayOverTime2[1]);
                } else {
                    echo '';
                }
            @endphp
        </td>
        <!--nemeh-->
@endfor

<!-- Display total hours for the whole month -->
<tr style="background-color: #82e2a2;">
    <td colspan="2" style="text-align: right;">合計時間:</td>
    <td>{{ floor($totalMinutesForMonth / 60) . ':' . ($totalMinutesForMonth %= 60) }}時間 </td>
</tr>
