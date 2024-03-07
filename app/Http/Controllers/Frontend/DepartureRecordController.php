<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyDepartureRecordRequest;
use App\Http\Requests\StoreDepartureRecordRequest;
use App\Http\Requests\UpdateDepartureRecordRequest;
use App\Models\ArrivalRecord;
use App\Models\DepartureRecord;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DepartureRecordController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('departure_record_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departureRecords = DepartureRecord::with(['arrival'])->get();

        return view('frontend.departureRecords.index', compact('departureRecords'));
    }

    public function create()
    {
        abort_if(Gate::denies('departure_record_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $arrivals = ArrivalRecord::pluck('recorded_at', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.departureRecords.create', compact('arrivals'));
    }

    public function store(StoreDepartureRecordRequest $request)
    {
        $departureRecord = DepartureRecord::create($request->all());

        return redirect()->route('frontend.departure-records.index');
    }

    public function edit(DepartureRecord $departureRecord)
    {
        abort_if(Gate::denies('departure_record_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $arrivals = ArrivalRecord::pluck('recorded_at', 'id')->prepend(trans('global.pleaseSelect'), '');

        $departureRecord->load('arrival');

        return view('frontend.departureRecords.edit', compact('arrivals', 'departureRecord'));
    }

    public function update(UpdateDepartureRecordRequest $request, DepartureRecord $departureRecord)
    {
        $departureRecord->update($request->all());

        return redirect()->route('frontend.departure-records.index');
    }

    public function show(DepartureRecord $departureRecord)
    {
        abort_if(Gate::denies('departure_record_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departureRecord->load('arrival');

        return view('frontend.departureRecords.show', compact('departureRecord'));
    }

    public function destroy(DepartureRecord $departureRecord)
    {
        abort_if(Gate::denies('departure_record_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departureRecord->delete();

        return back();
    }

    public function massDestroy(MassDestroyDepartureRecordRequest $request)
    {
        $departureRecords = DepartureRecord::find(request('ids'));

        foreach ($departureRecords as $departureRecord) {
            $departureRecord->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
