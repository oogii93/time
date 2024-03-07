<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyArrivalRecordRequest;
use App\Http\Requests\StoreArrivalRecordRequest;
use App\Http\Requests\UpdateArrivalRecordRequest;
use App\Models\ArrivalRecord;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArrivalRecordController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('arrival_record_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $arrivalRecords = ArrivalRecord::with(['user'])->get();

        return view('frontend.arrivalRecords.index', compact('arrivalRecords'));
    }

    public function create()
    {
        abort_if(Gate::denies('arrival_record_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.arrivalRecords.create', compact('users'));
    }

    public function store(StoreArrivalRecordRequest $request)
    {
        $arrivalRecord = ArrivalRecord::create($request->all());

        return redirect()->route('frontend.arrival-records.index');
    }

    public function edit(ArrivalRecord $arrivalRecord)
    {
        abort_if(Gate::denies('arrival_record_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $arrivalRecord->load('user');

        return view('frontend.arrivalRecords.edit', compact('arrivalRecord', 'users'));
    }

    public function update(UpdateArrivalRecordRequest $request, ArrivalRecord $arrivalRecord)
    {
        $arrivalRecord->update($request->all());

        return redirect()->route('frontend.arrival-records.index');
    }

    public function show(ArrivalRecord $arrivalRecord)
    {
        abort_if(Gate::denies('arrival_record_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $arrivalRecord->load('user', 'arrivalDepartureRecords');

        return view('frontend.arrivalRecords.show', compact('arrivalRecord'));
    }

    public function destroy(ArrivalRecord $arrivalRecord)
    {
        abort_if(Gate::denies('arrival_record_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $arrivalRecord->delete();

        return back();
    }

    public function massDestroy(MassDestroyArrivalRecordRequest $request)
    {
        $arrivalRecords = ArrivalRecord::find(request('ids'));

        foreach ($arrivalRecords as $arrivalRecord) {
            $arrivalRecord->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}