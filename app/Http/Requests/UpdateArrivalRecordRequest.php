<?php

namespace App\Http\Requests;

use App\Models\ArrivalRecord;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateArrivalRecordRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('arrival_record_edit');
    }

    public function rules()
    {
        return [
            'user_id' => [
                'required',
                'integer',
            ],
            'recorded_at' => [
                'required',
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
            ],
        ];
    }
}
