<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\ArrivalRecord;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response as FileResponse;
use Illuminate\Support\Facades\CSV;
use League\Csv\Writer;



class CSVController extends Controller
{
    public function index()
    {
        return view('admin.CSV.index');

    }

    protected function userTimeReportCollect($user,$startDate,$endDate,$workDayMinutes,$totalWorkDay,$totalWeekend)
    {

        $totalWorkedDay=0;
        $totalWorkedHoliday=0;
        $totalWorkedTime='00:00:00';
        $totalCountLate=0;
        $totalCountEarly=0;
        $totalPaidHoliday=0.0;
        $totalPaidNoWorkDay=0;

        $totalOtherHoliday=0;
        $totalAmarsanAjiliinUdur=0;
        $totalOverWorkedTimeA='00:00:00';
        $totalOverWorkedTimeB='00:00:00';
        $totalOverWorkedTimeC='00:00:00';
        $totalOverWorkedTimeD='00:00:00';



        $arrival_records=$user->userArrivalRecords()->whereDate('recorded_at','<',$endDate)->whereDate('recorded_at','>',$startDate)-> get();
        $countWorkedDay=0;
        foreach ($arrival_records as $arrival_record)
        {


            $date=Carbon::parse($arrival_record->recorded_at);
            if($date->isWeekend())
            {
                $totalWorkedHoliday++;
            }
            else{
                $countWorkedDay++;
            }
            $startTime=$date->copy()->format('H:i');
            $endTime=0;
            if($arrival_record->DepartureRecord)
            {
                $endTime=explode(' ',$arrival_record->DepartureRecord->recorded_at)[1];
            }
            if($startTime && $endTime ){
                $result = workTimeCalc($startTime,$endTime);

                $overTime2 = $result['overTime2'];

                // Convert workedTime to Carbon instance
                $carbonoverTime2 = Carbon::createFromFormat('H:i:s', $overTime2);

                // Get the total minutes
                $currentDayover2TotalMinutes = $carbonoverTime2->hour * 60 + $carbonoverTime2->minute;


                if($totalOverWorkedTimeC!='00:00:00')
                {
                    $arrayTotalOverTime2=explode(':',$totalOverWorkedTimeC);
                    $totalOverTime2ToMinutes=$arrayTotalOverTime2[0]*60+$arrayTotalOverTime2[1] ;

                }
                else
                {
                    $totalOverTime2ToMinutes=0;
                }
                $totalOverTime2ToMinutes+=$currentDayover2TotalMinutes;
                $totalOverWorkedTimeC=sprintf('%02d:%02d:00',floor($totalOverTime2ToMinutes/60),$totalOverTime2ToMinutes%=60);

                $overTime1 = $result['overTime1'];

                // Convert workedTime to Carbon instance
                $carbonoverTime1 = Carbon::createFromFormat('H:i:s', $overTime1);

                // Get the total minutes
                $currentDayover1TotalMinutes = $carbonoverTime1->hour * 60 + $carbonoverTime1->minute;


                if($totalOverWorkedTimeB!='00:00:00')
                {
                    $arrayTotalOverTime1=explode(':',$totalOverWorkedTimeB);
                    $totalOverTime1ToMinutes=$arrayTotalOverTime1[0]*60+$arrayTotalOverTime1[1]+$totalOverTime2ToMinutes ;

                }
                else
                {
                    $totalOverTime1ToMinutes=$totalOverTime2ToMinutes;
                }
                $totalOverTime1ToMinutes+=$currentDayover1TotalMinutes;
                $totalOverWorkedTimeB=sprintf('%02d:%02d:00',floor($totalOverTime1ToMinutes/60),$totalOverTime1ToMinutes%=60);


                $workedTime = $result['workedTime'];


                // Convert workedTime to Carbon instance
                $carbonWorkedTime = Carbon::createFromFormat('H:i:s', $workedTime);

                // Get the total minutes
                $currentDayTotalMinutes = $carbonWorkedTime->hour * 60 + $carbonWorkedTime->minute;

               $totalWorkedDay+=$currentDayTotalMinutes/$workDayMinutes;

                if($totalWorkedTime!='00:00:00')
                {
                    $arrayTotalWorkedTime=explode(':',$totalWorkedTime);
                    $totalWorkedTimeToMinutes=$arrayTotalWorkedTime[0]*60+$arrayTotalWorkedTime[1]+$totalOverTime1ToMinutes;

                }
                else
                {
                    $totalWorkedTimeToMinutes=$totalOverTime1ToMinutes;
                }
                $totalWorkedTimeToMinutes+=$currentDayTotalMinutes;
                $totalWorkedTime=sprintf('%02d:%02d:00',floor($totalWorkedTimeToMinutes/60),$totalWorkedTimeToMinutes%=60);

                if($result['countLate'])
                {
                    $totalCountLate++;
                }
                if($result['countEarly'])
                {
                    $totalCountEarly++;
                }



                // echo "$totalWorkedTime<br><br>$totalOverTime1ToMinutes<br>";

            }


        }
        // dd($totalWorkedTimeToMinutes,$totalOverTime1ToMinutes,$totalOverTime2ToMinutes);

        return [
            'staff_number'=>$user->staff_number,
            'name'=>$user->name,
            'workedDay'=>$totalWorkedDay,
           'workedHoliday'=> $totalWorkedHoliday,
           'workedTime'=> $totalWorkedTime,
           'countLate'=> $totalCountLate,
            'countEarly'=>$totalCountEarly,
            'paidHoliday'=>$totalPaidHoliday,
            'paidNoWorkDay'=>$totalPaidNoWorkDay,
            'weekend'=>$totalWeekend,
           'otherHoliday'=> $totalOtherHoliday,
            'amarsanAjiliinUdur'=>$totalAmarsanAjiliinUdur,
            'absentDay'=>$totalWorkDay-$totalWorkedDay,
            'overWorkedTimeA'=>$totalOverWorkedTimeA,
            'overWorkedTimeB'=>$totalOverWorkedTimeB,
           'overWorkedTimeC'=> $totalOverWorkedTimeC,
            'overWorkedTimeD'=>$totalOverWorkedTimeD,
        ];
    }
    public function download(Request $request)
    {

        $workDayMinutes=7*60+40;
        $month=$request->month;
        $endDate=Carbon::parse(date("$month-16"));
        $startDate=Carbon::parse(date("$month-16"))->subMonth();
        $startDateForCountWeekend=Carbon::parse(date("$month-16"))->subMonth();
        $startDateForCountWorkDay=Carbon::parse(date("$month-16"))->subMonth();
        $totalWeekend = 0;
        while ($startDateForCountWeekend->lte($endDate)) {
            if ($startDateForCountWeekend->isWeekend()) {
                $totalWeekend++;
            }
            $startDateForCountWeekend->addDay(); // Move to the next day
        }
        $totalWorkDay = 0;
        while ($startDateForCountWorkDay->lte($endDate)) {
            if (!$startDateForCountWorkDay->isWeekend()) {
                $totalWorkDay++;
            }
            $startDateForCountWorkDay->addDay(); // Move to the next day
        }


        $row=[

        ];

        $users=User::get();
        foreach($users as $user)
       {
        $row[]=$this->userTimeReportCollect($user,$startDate,$endDate,$workDayMinutes,$totalWorkDay,$totalWeekend);

       }



        $headers = [
            'Content-Type' => 'text/csv; charset=Shift-JIS',
            'Content-Disposition' => 'attachment; filename="'.$month.'.csv"',
        ];

        // Initialize CSV file
        $csv = \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
        $csv = Writer::createFromFileObject(new \SplTempFileObject());
$csv->setOutputBOM(Writer::BOM_UTF8); // Add BOM to ensure correct encoding



        // Insert headers into CSV
        $csv->insertOne([
            '社員番号(必須)',
            '社員氏名(ﾃﾝﾌﾟﾚｰﾄ項目)',
            '平日出勤',
            '休日出勤',
            '出勤時間',
            '遅刻',
            '早退',
            '有休日数',
            '代休',
            '公休',
            'その他の休日',
            '休職日数',
            '欠勤日数',
            '時間外手当時間Ａ',
            '時間外手当時間Ｂ',
            '時間外手当時間Ｃ',
            '時間外手当時間Ｄ',
        ]);

        // Insert data into CSV
        foreach ($row as $values) {
            $csv->insertOne([
                $values['staff_number'],
                $values['name'],
                $values['workedDay'],
                $values['workedHoliday'],
                $values['workedTime'],
                $values['countLate'],
                $values['countEarly'],
                $values['paidHoliday'],
                $values['paidNoWorkDay'],
                $values['weekend'],
                $values['otherHoliday'],
                $values[ 'amarsanAjiliinUdur'],
                $values['absentDay'],
                $values['overWorkedTimeA'],
                $values['overWorkedTimeB'],
                $values['overWorkedTimeC'],
                $values[ 'overWorkedTimeD'],
            ]);
        }

        // Return CSV response
        return FileResponse::make($csv,200, $headers);

    }


}