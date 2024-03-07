<?php

namespace App\Http\Requests;

use App\Models\DepartureRecord;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyDepartureRecordRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('departure_record_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:departure_records,id',
        ];
    }
}
