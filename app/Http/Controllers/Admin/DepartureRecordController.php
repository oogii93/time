<?php

namespace App\Http\Controllers\Admin;

use Gate;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Department;
use Illuminate\Http\Request;
use App\Models\ArrivalRecord;
use App\Models\DepartureRecord;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreDepartureRecordRequest;
use App\Http\Requests\UpdateDepartureRecordRequest;
use App\Http\Requests\MassDestroyDepartureRecordRequest;

class DepartureRecordController extends Controller
{
    protected function toFormData()
    {
        $users=User::all();
        $departments=Department::all();
        return ['users'=>$users,'departments'=>$departments];
    }


    public function filter(Request $request)
    {
        abort_if(Gate::denies('departure_record_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

//data valdite hiih
        $data=$request->all();

        if(isset($request->from) && isset($request->to)) {
            $query=DepartureRecord::with(['arrival']);
            if($data['user_id'])
            {
                $query->whereHas('arrival', function($query) use ($data) {
                    $query->where('user_id', $data['user_id']);
                });

            }
            if ($data['department_id']) {
                $query->whereHas('arrival.user.staffsDepartments', function($query) use ($data) {
                    $query->where('department_id', $data['department_id']);
                });
            }

            $from=Carbon::parse($data['from'])->format('Y-m-d 08:30:00');
            $to=Carbon::parse($data['to'])->format('Y-m-d 23:59:59');
            $results =$query
            ->whereBetween('recorded_at',[$from,$to])
            ->get();


            $formData=$this->toFormData();
        return view('admin.departureRecords.filter' ,compact('results','formData'));

        }
    }
    public function index(Request $request)
    {
        abort_if(Gate::denies('departure_record_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = DepartureRecord::with(['arrival'])->select(sprintf('%s.*', (new DepartureRecord)->table));
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

            $table->addColumn('department', function ($row) {
               $department='no department';

                if($row->arrival)
                {
                    $user=$row->arrival->user;
                    if($user)
                    {
                        $department = $user->staffsDepartments->count()
                        ? implode(', ', $user->staffsDepartments->pluck('name')->toArray())
                        : 'no department';

                    }
                }
               return $department;
            });
            $table->addColumn('name', function ($row) {
                return $row->arrival ? $row->arrival->user->name : '';
            });

            $table->addColumn('arrival_recorded_at', function ($row) {
                return $row->arrival ? $row->arrival->recorded_at : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'arrival']);

            return $table->make(true);
        }
        $formData=$this->toFormData();

        return view('admin.departureRecords.index',compact('formData'));
    }


    public function create()
    {
        abort_if(Gate::denies('departure_record_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $arrivals = ArrivalRecord::pluck('recorded_at', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.departureRecords.create', compact('arrivals'));
    }

    public function store(StoreDepartureRecordRequest $request)
    {
        $departureRecord = DepartureRecord::create($request->all());

        return redirect()->route('admin.departure-records.index');
    }

    public function edit(DepartureRecord $departureRecord)
    {
        abort_if(Gate::denies('departure_record_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $arrivals = ArrivalRecord::pluck('recorded_at', 'id')->prepend(trans('global.pleaseSelect'), '');

        $departureRecord->load('arrival');

        return view('admin.departureRecords.edit', compact('arrivals', 'departureRecord'));
    }

    public function update(UpdateDepartureRecordRequest $request, DepartureRecord $departureRecord)
    {
        $departureRecord->update($request->all());

        return redirect()->route('admin.departure-records.index');
    }

    public function show(DepartureRecord $departureRecord)
    {
        abort_if(Gate::denies('departure_record_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $departureRecord->load('arrival');

        return view('admin.departureRecords.show', compact('departureRecord'));
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