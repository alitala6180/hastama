<?php

namespace App\Http\Controllers;


use App\Models\Leave;
use App\Models\Employee;

use App\Http\Requests\StoreLeaveRequest;
use App\Http\Requests\UpdateLeaveRequest;

use Illuminate\Http\Request;

use Inertia\Inertia;



class LeaveController extends Controller
{



    public function index()
    {


        $leaves = Leave::with([

            'employee:id,first_name,last_name,employee_code'

        ])

        ->latest()

        ->paginate(10);





        return Inertia::render(

            'Leaves/Index',

            [

                'leaves'=>$leaves

            ]

        );


    }









    public function create()
    {


        return Inertia::render(

            'Leaves/Create',

            [

                'employees'=>Employee::where(

                    'status',

                    'active'

                )

                ->select(

                    'id',

                    'first_name',

                    'last_name',

                    'employee_code'

                )

                ->get()

            ]

        );


    }









    public function store(StoreLeaveRequest $request)
    {


        abort_unless(
            $request->user()->can('leave.manage'),
            403
        );




        $data = $request->validated();




        $data['days'] = $this->calculateDays(

            $data['from_date'],

            $data['to_date']

        );




        Leave::create($data);





        return redirect()

            ->route('leaves.index')

            ->with(

                'success',

                'درخواست مرخصی ثبت شد'

            );


    }









    public function edit(Leave $leave)
    {


        abort_unless(
            request()->user()->can('leave.manage'),
            403
        );




        return Inertia::render(

            'Leaves/Edit',

            [


                'leave'=>$leave,



                'employees'=>Employee::where(

                    'status',

                    'active'

                )

                ->select(

                    'id',

                    'first_name',

                    'last_name',

                    'employee_code'

                )

                ->get()


            ]

        );


    }









    public function update(

        UpdateLeaveRequest $request,

        Leave $leave

    )
    {


        abort_unless(
            $request->user()->can('leave.manage'),
            403
        );





        $data = $request->validated();





        $data['days'] = $this->calculateDays(

            $data['from_date'],

            $data['to_date']

        );






        $leave->update($data);





        return redirect()

            ->route('leaves.index')

            ->with(

                'success',

                'مرخصی بروزرسانی شد'

            );


    }









    public function approve(Leave $leave)
    {


        abort_unless(
            request()->user()->can('leave.manage'),
            403
        );







        $leave->update([


            'status'=>'approved',


            'approved_by'=>request()->user()->id,


        ]);







        return back()

            ->with(

                'success',

                'مرخصی تایید شد'

            );


    }









    public function reject(Leave $leave)
    {


        abort_unless(
            request()->user()->can('leave.manage'),
            403
        );







        $leave->update([


            'status'=>'rejected',


            'approved_by'=>request()->user()->id,


        ]);







        return back()

            ->with(

                'success',

                'مرخصی رد شد'

            );


    }









    public function destroy(Leave $leave)
    {


        abort_unless(
            request()->user()->can('leave.manage'),
            403
        );







        $leave->delete();







        return back()

            ->with(

                'success',

                'درخواست مرخصی حذف شد'

            );


    }









    private function calculateDays(

        $start,

        $end

    )
    {


        $startDate = \Carbon\Carbon::parse($start);


        $endDate = \Carbon\Carbon::parse($end);




        return $startDate->diffInDays(

            $endDate

        ) + 1;


    }



}
