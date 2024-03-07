<?php

namespace App\Http\Requests;

use App\Models\DepartureRecord;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateDepartureRecordRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('departure_record_edit');
    }

    public function rules()
    {
        return [
            'arrival_id' => [
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
