<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyArrivalRecordRequest;
use App\Http\Requests\StoreArrivalRecordRequest;
use App\Http\Requests\UpdateArrivalRecordRequest;
use App\Models\ArrivalRecord;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ArrivalRecordController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('arrival_record_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = ArrivalRecord::with(['user'])->select(sprintf('%s.*', (new ArrivalRecord)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'arrival_record_show';
                $editGate      = 'arrival_record_edit';
                $deleteGate    = 'arrival_record_delete';
                $crudRoutePart = 'arrival-records';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->addColumn('user_name', function ($row) {
                return $row->user ? $row->user->name : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'user']);

            return $table->make(true);
        }

        return view('admin.arrivalRecords.index');
    }

    public function create()
    {
        abort_if(Gate::denies('arrival_record_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.arrivalRecords.create', compact('users'));
    }

    public function store(StoreArrivalRecordRequest $request)
    {
        $arrivalRecord = ArrivalRecord::create($request->all());

        return redirect()->route('admin.arrival-records.index');
    }

    public function edit(ArrivalRecord $arrivalRecord)
    {
        abort_if(Gate::denies('arrival_record_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $arrivalRecord->load('user');

        return view('admin.arrivalRecords.edit', compact('arrivalRecord', 'users'));
    }

    public function update(UpdateArrivalRecordRequest $request, ArrivalRecord $arrivalRecord)
    {
        $arrivalRecord->update($request->all());

        return redirect()->route('admin.arrival-records.index');
    }

    public function show(ArrivalRecord $arrivalRecord)
    {
        abort_if(Gate::denies('arrival_record_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $arrivalRecord->load('user', 'arrivalDepartureRecords');

        return view('admin.arrivalRecords.show', compact('arrivalRecord'));
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
