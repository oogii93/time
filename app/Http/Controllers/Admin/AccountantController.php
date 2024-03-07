<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\ArrivalRecord;
use App\Http\Controllers\Controller;

class AccountantController extends Controller
{
    public function index()
    {
        return view('admin.accountant.index');
    }

    public function getDepartment(Request $request)
    {
        if (!$request->id)
        {
            $users=User::all();
        }
        else{

            $department = Department::find($request->id);
            $users=$department->staffs;
        }
        $option = '';
        foreach ($users as $user) {
            $option .= '<option value="' . $user->id . '">' . $user->name . '</option>';
        }
        return response($option);
    }

    public function filter(Request $request)
    {
        $data = $request->all();

        $enddate = Carbon::parse(date('Y-' . $data['from_month'] . '-16'))->format('Y-m-16');
        $startdate = Carbon::parse(date('Y-' . $data['from_month'] . '-16'))->subMonth()->format('Y-m-16');


        $arrivals = ArrivalRecord::where('user_id', $data['user_id'])
            ->whereDate('recorded_at', '>=', $startdate)
            ->whereDate('recorded_at', '<=', $enddate)
            ->get();


        return view('admin.accountant.index', compact('arrivals', 'startdate', 'enddate'));
    }
//     public function test($startTime ='8:30', $endTime='17:30')
// {
// dd($startTime,$endTime);
// //tsag bodolt hiih shalgah
// //if-eer
// // 10-aas 12 tsagiin hoorond
// }
// public function test($startTime = '8:30', $endTime = '21:30')
// {
//     // Convert start and end time strings to DateTime objects
//     $start = \DateTime::createFromFormat('H:i', $startTime);
//     $end = \DateTime::createFromFormat('H:i', $endTime);

//     // If the start time is 8:30 and end time is 17:30
//     if ($startTime === '8:30' && $endTime === '17:30') {
//         // Subtract 80 minutes from the end time
//         $end->sub(new \DateInterval('PT80M'));

//         // Calculate the time difference from 8:30 to 17:30
//         $diff1 = $start->diff($end);
//         echo "Time between " . $start->format('H:i') . " and " . $end->format('H:i') . " is: <br>";
//         echo $diff1->format('%H:%I') . " hours<br>";
//     }

//     // Calculate the time difference from 17:40 to the end time
//     $endOfDayPlusTen = \DateTime::createFromFormat('H:i', '17:40');
//     $diff2 = $endOfDayPlusTen->diff($end);
//     echo "Time between 17:40 and " . $end->format('H:i') . " is: <br>";
//     echo $diff2->format('%H:%I') . " hours<br>";
// }











}