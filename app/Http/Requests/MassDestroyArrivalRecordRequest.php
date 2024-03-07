<?php

namespace App\Http\Requests;

use App\Models\ArrivalRecord;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyArrivalRecordRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('arrival_record_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:arrival_records,id',
        ];
    }
}
