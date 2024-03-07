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



class CSVDayController extends Controller
{
    public function index()
    {
        return view('admin.CSVDay.index');

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

        $enddate = Carbon::parse(date('Y-' . $data['from_month'] . '-15'))->format('Y-m-15');
        $startdate = Carbon::parse(date('Y-' . $data['from_month'] . '-16'))->subMonth()->format('Y-m-16');


        $arrivals = ArrivalRecord::where('user_id', $data['user_id'])
            ->whereDate('recorded_at', '>=', $startdate)
            ->whereDate('recorded_at', '<=', $enddate)
            ->get();


        return view('admin.CSVDay.index', compact('arrivals', 'startdate', 'enddate'));
    }
    // public function filter(Request $request)
    // {
    //     $data = $request->all();

    //     // Fetch all users
    //     $users = User::all();

    //     // Further filtering based on the selected month if needed

    //     // Fetch arrivals records based on start date and end date for all users
    //     // Adjust this part according to your requirements
    //     $fromMonth = $data['from_month'];
    //     $enddate = Carbon::parse(date('Y-' . $fromMonth . '-15'))->format('Y-m-15');
    //     $startdate = Carbon::parse(date('Y-' . $fromMonth . '-16'))->subMonth()->format('Y-m-16');

    //     $arrivals = ArrivalRecord::whereIn('user_id', $users->pluck('id'))
    //         ->whereDate('recorded_at', '>=', $startdate)
    //         ->whereDate('recorded_at', '<=', $enddate)
    //         ->get();

    //     // Return the view with arrivals, users, start date, and end date data
    //     return view('admin.CSVDay.index', compact('arrivals', 'startdate', 'enddate', 'users'));
    // }




}