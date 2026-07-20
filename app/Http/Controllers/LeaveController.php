<?php

namespace App\Http\Controllers;


use App\Models\Leave;
use App\Models\Employee;
use App\Models\LeaveBalance;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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




        // بررسی مانده مرخصی سالانه

        if($data['type'] === 'annual'){


            $balance = LeaveBalance::firstOrCreate([

                'employee_id'=>$data['employee_id'],

                'year'=>Carbon::parse($data['from_date'])->year,

            ],[

                'annual_days'=>26,

                'used_days'=>0,

            ]);



            $remaining = $balance->annual_days - $balance->used_days;



            abort_if(

                $data['days'] > $remaining,

                422,

                'مانده مرخصی کافی نیست'

            );


        }



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


        DB::transaction(function() use($leave){


            $leave->load('employee');


            if(
                $leave->type === 'annual'
            ){


                $year = Carbon::parse(
                    $leave->from_date
                )->year;



                $balance = LeaveBalance::firstOrCreate([

                    'employee_id'=>$leave->employee_id,

                    'year'=>$year,

                ],[

                    'annual_days'=>26,

                    'used_days'=>0,

                ]);





                if(
                    $balance->used_days + $leave->days
                    >
                    $balance->annual_days
                ){

                    abort(
                        422,
                        'مانده مرخصی کافی نیست'
                    );

                }





                $balance->increment(
                    'used_days',
                    $leave->days
                );

            }





            $leave->update([


                'status'=>'approved',


                'approved_by'=>request()->user()->id,


            ]);



        });



        return back()->with(

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


        DB::transaction(function() use($leave){



            if(
                $leave->status === 'approved'
                &&
                $leave->type === 'annual'
            ){


                $year = Carbon::parse(
                    $leave->from_date
                )->year;



                LeaveBalance::where([

                    'employee_id'=>$leave->employee_id,

                    'year'=>$year,

                ])

                ->decrement(

                    'used_days',

                    $leave->days

                );


            }




            $leave->update([


                'status'=>'rejected',


                'approved_by'=>request()->user()->id,


            ]);



        });




        return back()->with(

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







        DB::transaction(function() use($leave){


            if(
                $leave->status === 'approved'
                &&
                $leave->type === 'annual'
            ){


                $year = Carbon::parse(
                    $leave->from_date
                )->year;



                LeaveBalance::where([

                    'employee_id'=>$leave->employee_id,

                    'year'=>$year,

                ])

                ->decrement(

                    'used_days',

                    $leave->days

                );


            }



            $leave->delete();


        });







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
