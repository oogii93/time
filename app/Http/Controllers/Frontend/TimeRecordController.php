<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\ArrivalRecord;
use App\Models\DepartureRecord;
use App\Rules\RecordedAtExistsRule;
use App\Http\Controllers\Controller;

class TimeRecordController extends Controller
{
    public function record(Request $request)
    {
        $message="出勤登録されました";
        $user= $request->user();
        // ehleed irsen tsagiig shalgah
        $arrival = ArrivalRecord::where('user_id',$user->id)->whereDate('recorded_at', today())->first();

        // irsen bhgui bol irseng burtgeh ywsan tsagiig burtguuleh bsan ch
        if(empty($arrival)){
            $arrival =$user->userArrivalRecords()->create([
                'recorded_at' => now(),
            ]);
        }
        else{
            $message="出勤時間登録されています";
        }

        if(isset($request->record_departure)){
            // ywsan tsag burtgel
            $departure =$arrival->arrivalDepartureRecords()->whereDate('recorded_at', today())->first();
            if(empty($departure)){
                $departure = $arrival->arrivalDepartureRecords()->create([
                    'recorded_at' => now(),
                ]);
                $message.=" 退勤時間登録されました。";
            } else {
                // ywsan tsag burtgegdsen bol update
                $departure->update([
                    'recorded_at' => now(),
                ]);
                $message.=" 退勤時間登録がアップデートされました";
            }

        }


        return redirect()->route('frontend.home')->with('status', $message); // japanaar bicheerei
    }

    public function record_manual(Request $request)
    {
        $data = $request->validate([
            'recorded_at' => ['required', 'date'],
            'button' => ['required', 'string', 'in:ArrivalRecord,DepartureRecord'],
        ]);

        $inputDate = \Carbon\Carbon::parse($data['recorded_at']);
        $data['recorded_at'] = $inputDate;

        $user = $request->user();

        if ($data['button'] == 'ArrivalRecord') {
            $exist = ArrivalRecord::where('user_id', $user->id)
                ->whereDate('recorded_at', $inputDate->format('Y-m-d'))
                ->first();

            if ($exist) {
                $exist->update($data);
                return redirect()->route('frontend.home')->with('status', '時間が登録されています。');
            } else {
                $user->userArrivalRecords()->create($data);
                return redirect()->route('frontend.home')->with('status', '出勤時間が登録されました。');
            }
        } else {
            if($inputDate->hour < 8)
            {
                $inputDate->subDay();
            }

            $exist = ArrivalRecord::where('user_id', $user->id)

                ->whereDate('recorded_at', $inputDate->format('Y-m-d'))
                ->first();

            if ($exist) {

                $departureExist = $exist->arrivalDepartureRecords()->whereDate('recorded_at', $inputDate->format('Y-m-d'))->first();

                if ($departureExist) {
                    $departureExist->update($data);
                    return redirect()->route('frontend.home')->with('status', '退社時間が更新されました。');
                } else {
                    $exist->arrivalDepartureRecords()->create($data);
                    return redirect()->route('frontend.home')->with('status', '退勤時間登録完了。');
                }
            } else {
                $arrival = $user->userArrivalRecords()->create($data);
                $arrival->arrivalDepartureRecords()->create($data);
                return redirect()->route('frontend.home')->with('status', '出退勤時間が登録完了しました。');
            }
        }
    }
}
