<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DepartureRecord;
use App\Http\Requests\MassDestroyDepartureRecordRequest;
use App\Http\Requests\StoreDepartureRecordRequest;
use App\Http\Requests\UpdateDepartureRecordRequest;
use App\Models\ArrivalRecord;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;


class ReportController extends Controller
{
    public function index(Request $request ){
        abort_if(Gate::denies('departure_record_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        if(isset($request->from_date) && isset($request->to_date)){
            $query = DepartureRecord::with(['arrival'])
            ->whereDate('recorded_at', '=>', $request->from_date)
            ->whereDate('recorded_at', '<=', $request->to_date)
            ->select(sprintf('%s.*', (new DepartureRecord)->table));

        }
        else{
            $query = DepartureRecord::with(['arrival'])->select(sprintf('%s.*', (new DepartureRecord)->table));

        }
        if ($request->ajax()) {


            //sjklfsd

            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'departure_record_show';
                $editGate      = 'departure_record_edit';
                $deleteGate    = 'departure_record_delete';
                $crudRoutePart = 'departure-records';

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
            $table->addColumn('name', function ($row) {
                if ($row->arrival){
                    return $row->arrival->user ?  $row->arrival->user->name : '';
                }
                else
                {
                    return 'unnamed';
                }

            });
            $table->addColumn('arrival_recorded_at', function ($row) {
                return $row->arrival ? $row->arrival->recorded_at : '';
            });


            $table->rawColumns(['actions', 'placeholder', 'arrival']);

            return $table->make(true);
        }

        return view('admin.reports.index');


    }

    public function filter(Request $request)
    {

        $data =$request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);
        //  dd($data);

        abort_if(Gate::denies('departure_record_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DepartureRecord::with(['arrival'])
            ->whereDate('recorded_at', '=>', $request->from_date)
            ->whereDate('recorded_at', '<=', $request->to_date)
            ->select(sprintf('%s.*', (new DepartureRecord)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'departure_record_show';
                $editGate      = 'departure_record_edit';
                $deleteGate    = 'departure_record_delete';
                $crudRoutePart = 'departure-records';

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
            $table->addColumn('name', function ($row) {
                if ($row->arrival){
                    return $row->arrival->user ?  $row->arrival->user->name : '';
                }
                else
                {
                    return 'unnamed';
                }

            });
            $table->addColumn('arrival_recorded_at', function ($row) {
                return $row->arrival ? $row->arrival->recorded_at : '';
            });


            $table->rawColumns(['actions', 'placeholder', 'arrival']);

            return $table->make(true);
        }

        return view('admin.reports.index');
    }

}
