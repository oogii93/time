<?php

namespace App\Http\Controllers\Frontend;

use Carbon\Carbon;

class HomeController
{
    public function index()
    {
        $year = date('Y');


        $user=auth()->user();
        $month=date('m');
        $tbody=view('frontend.includes.time-table-body',[
            'user'=>$user,
            'month'=>$month,
            'year'=>$year,
            ])->render();
        return view('frontend.home' ,compact('tbody'));

    }

    public function omnoh($year,$month)
    {

        $year=(int)$year;
        $month=(int)$month;
       if(!$month||!$year) return abort(404);
        $user=auth()->user();
        $tbody=view('frontend.includes.time-table-body',[
            'user'=>$user,
            'month'=>$month,
            'year'=>$year,
            ])->render();

        return view('frontend.home' ,compact('tbody'));
    }
}