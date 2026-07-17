<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index(Request $request)
    {
        $leaves = Leave::query()

            ->with([

                'employee:id,first_name,last_name,employee_code',

                'approver:id,name',

            ])

            ->when($request->status, function ($query, $status) {

                $query->where('status', $status);

            })

            ->latest()

            ->paginate(10)

            ->withQueryString();


        return Inertia::render('Leaves/Index', [

            'leaves' => $leaves,

            'filters' => [

                'status' => $request->status,

            ],

        ]);
    }


    public function create()
    {
        return Inertia::render('Leaves/Create', [

            'employees' => Employee::query()

                ->select(
                    'id',
                    'first_name',
                    'last_name',
                    'employee_code'
                )

                ->where('status','active')

                ->orderBy('first_name')

                ->get(),

        ]);
    }

    public function store(StoreLeaveRequest $request)
    {

        $employee = Employee::findOrFail(

            $request->employee_id

        );


        $used = Leave::query()

            ->where('employee_id',$employee->id)

            ->where('status','approved')

            ->where('type','annual')

            ->sum('days');


        $remaining =

            $employee->annual_leave

            -

            $used;


        if(

            $request->type == 'annual'

            &&

            $request->days > $remaining

        ){

            return back()

                ->withErrors([

                    'days'=>

                    'موجودی مرخصی کافی نیست.'

                ]);

        }


        Leave::create(

            $request->validated()

        );


        return redirect()

            ->route('leaves.index')

            ->with(

                'success',

                'مرخصی ثبت شد.'

            );

    }

    public function show(Leave $leave)
    {
        //
    }

    public function edit(Leave $leave)
    {
        //
    }

    public function update(Request $request, Leave $leave)
    {
        //
    }

    public function destroy(Leave $leave)
    {
        //
    }

    public function approve(Leave $leave)
    {

        $leave->update([

            'status' => 'approved',

            'approved_by' => Auth::id(),

            'approved_at' => now(),

        ]);


        return back()->with(
            'success',
            'مرخصی تایید شد'
        );

    }

    public function reject(Leave $leave)
    {

        $leave->update([

            'status' => 'rejected',

            'approved_by' => Auth::id(),

            'approved_at' => now(),

        ]);


        return back()->with(
            'success',
            'مرخصی رد شد'
        );

    }
}