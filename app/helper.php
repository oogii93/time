<?php

use Carbon\Carbon;

function workTimeCalc($startTime = '8:50', $endTime = '22:30')
{
    $result = [

        'workedTime' => '00:00:00',
        'countLate' => 0,
        'countEarly' => 0,
        'overTime1' => '00:00:00',
        'overTime2' => '00:00:00',
        'absentTime'=>'00:00:00',

    ];

    $sTime = Carbon::parse($startTime);
    $eTime = Carbon::parse($endTime);

    if ($eTime->lessThan($sTime)) {
        // Add a day to end time if it's before start time, assuming it spans to the next day
        $eTime->addDay();
    }

    $timeRange1Start = Carbon::parse('08:30');
    $timeRange1End = Carbon::parse('12:00');
    $timeRange2Start = Carbon::parse('13:00');
    $timeRange2End = Carbon::parse('17:30');
    $timeRangeOver1Start=Carbon::parse('17:40');
    $timeRangeOver1End=Carbon::parse('22:00');
    $timeRangeOver2Start=Carbon::parse('22:00');
    $timeRangeOver2End=Carbon::parse('07:30')->addDay();

    // If end time exceeds the second time range, adjust it to the end of the second range
    $totalAbsentMinutes=0;
    if ($eTime->greaterThan($timeRange2End)) {
        // Calculate the difference in hours between arrival and departure times
        $normalDayEndTime=$timeRange2End;

    }else{
        $normalDayEndTime=$eTime;
        $result['countEarly']=1;
        $totalAbsentMinutes+=$eTime->diffInMinutes($timeRange2End);

    }
    if ($sTime->greaterThan($timeRange1Start)){
        $result['countLate']=1;
        $totalAbsentMinutes+=$sTime->diffInMinutes($timeRange1Start);
    }
    $result['absentTime']=sprintf('%02d:%02d:00',floor($totalAbsentMinutes / 60),$totalAbsentMinutes%=60);

    // $hoursBetween = $sTime->diffInHours($normalDayEndTime);
        $totalMinutes = $sTime->diffInMinutes($normalDayEndTime);//tsainii tsag hasaj tootsow
       if ($totalMinutes>240)
       {
        $totalMinutes-=60;
       }

        // Initialize counters for each time range
        // $countInRange1 = 0;
        // $countInRange2 = 0;

        // Iterate over each hour in the range and count full hours
        // for ($i = 1; $i <= $hoursBetween; $i++) {
        //     $currentHour = $sTime->copy()->addHours($i);

        //     // Check if the current hour falls within time range 1
        //     if ($currentHour->between($timeRange1Start, $timeRange1End)) {
        //         $countInRange1++;
        //     }
        //     // Check if the current hour falls within time range 2
        //     elseif ($currentHour->between($timeRange2Start, $timeRange2End)) {
        //         $countInRange2++;
        //     }
        // }
            $adjustedMinutes=0;

          if ($sTime->lessThan($timeRange1Start && $sTime->greaterThan($timeRange1End)))
          {
            $adjustedMinutes+=10;
          }

          if ($sTime->lessThan($timeRange2Start && $sTime->greaterThan($timeRange2End)))
          {
            $adjustedMinutes+=10;
          }
        // if ($sTime->greaterThan($timeRange1Start) && $sTime->lessThan($timeRange1End)) {
        //     $adjustedMinutes += 10;
        // }

        // if ($sTime->greaterThan($timeRange2Start) && $sTime->lessThan($timeRange2End)) {
        //     $adjustedMinutes += 10;
        // }

        // $adjustedMinutes = $countInRange1 * 10 + $countInRange2 * 10;
        //   if($result['countLate']&& $eTime->greaterThan($timeRange2End))
        //   {
        //     dd($totalAbsentMinutes);
        //   }
        $workedMinutes = $totalMinutes - $adjustedMinutes;
        $workedHours = floor($workedMinutes / 60); // Extract the hours
        $workedMinutes %= 60; // Extract the remaining minutes

        // dd($totalMinutes,$countInRange1,$countInRange2,$adjustedMinutes,$workedHours,$workedMinutes);
        // Format the worked hours and minutes as H:i
        $workedHoursFormatted =sprintf('%02d:%02d:00',$workedHours ,$workedMinutes);


        // Assign calculated values to result array

        $result['workedTime'] = $workedHoursFormatted;
            $totalOverMinutes1=0;
        if($eTime->greaterThan($timeRangeOver1Start))
        {
            if($eTime->greaterThan($timeRangeOver1End))
            {
                $overE1Time=$timeRangeOver1End;
            }
            else
            {
                $overE1Time=$eTime;

            }
            $totalOverMinutes1+=$timeRangeOver1Start->diffInMinutes($overE1Time);
            $result['overTime1']=sprintf('%02d:%02d:00', floor($totalOverMinutes1 /60) ,$totalOverMinutes1%=60);
        }

        if($eTime->greaterThan($timeRangeOver2Start))
        {
            if($eTime->greaterThan($timeRangeOver2End))
            {
                $overE2Time=$timeRangeOver2End;
            }
            else
            {
                $overE2Time=$eTime;
            }
            $totalOverMinutes2=$timeRangeOver2Start->diffInMinutes($overE2Time);
            $result['overTime2']=sprintf('%02d:%02d:00', floor($totalOverMinutes2 /60) ,$totalOverMinutes2%=60);
        }




    // dd($result,$startTime,$endTime);



    // Implement other logic as needed

    return $result;
}