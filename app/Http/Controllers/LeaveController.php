<?php

namespace App\Http\Controllers;


use App\Models\Leave;
use App\Models\Employee;

use App\Http\Requests\StoreLeaveRequest;

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

            $request->user()->can('leaves.create'),

            403

        );




        Leave::create(

            $request->validated()

        );




        return redirect()

            ->route('leaves.index')

            ->with(

                'success',

                'درخواست مرخصی ثبت شد'

            );


    }











    public function approve(

        Leave $leave

    )
    {


        abort_unless(

            request()->user()->can('leaves.approve'),

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









    public function reject(

        Leave $leave

    )
    {


        abort_unless(

            request()->user()->can('leaves.approve'),

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



}