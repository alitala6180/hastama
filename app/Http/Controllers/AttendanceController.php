<?php

namespace App\Http\Controllers;


use App\Models\Attendance;
use App\Models\Employee;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Inertia\Inertia;



class AttendanceController extends Controller
{


    public function index(Request $request)
    {

        $date = $request->date ?? now()->toDateString();



        $attendances = Attendance::with([

            'employee:id,first_name,last_name,employee_code'

        ])

        ->whereDate(
            'work_date',
            $date
        )

        ->latest()

        ->paginate(10)

        ->withQueryString();






        $employees = Employee::where(
            'status',
            'active'
        )

        ->select(

            'id',

            'first_name',

            'last_name',

            'employee_code',

            'shift_id'

        )

        ->with([

            'shift:id,name,start_time,end_time',

            'attendances'=>function($query) use ($date){

                $query
                    ->whereDate(
                        'work_date',
                        $date
                    )
                    ->latest();

            }

        ])

        ->get();





        return Inertia::render(
            'Attendances/Index',
            [

                'attendances'=>$attendances,

                'employees'=>$employees,

                'filters'=>[

                    'date'=>$date

                ]

            ]
        );

    }








    public function create()
    {

        return Inertia::render(
            'Attendances/Create',
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








    public function store(Request $request)
    {

        abort_unless(
            $request->user()->can('attendance.manage'),
            403
        );



        $validated = $request->validate([


            'employee_id'=>'required|exists:employees,id',


            'work_date'=>'required|date',


            'check_in'=>'required',


            'check_out'=>'nullable',


        ]);






        $request->validate([


            'employee_id'=>[

                Rule::unique('attendances','employee_id')

                ->where(
                    'work_date',
                    $validated['work_date']
                )

            ]

        ]);






        $employee = Employee::with('shift')

            ->findOrFail(
                $validated['employee_id']
            );







        $times = $this->calculateTimes(

            $employee,

            $validated['work_date'],

            $validated['check_in'],

            $validated['check_out']

        );







        Attendance::create([

            ...$validated,

            ...$times,

        ]);







        return redirect()

            ->route('attendances.index')

            ->with(

                'success',

                'حضور و غیاب ثبت شد'

            );

    }









    public function checkIn(Employee $employee)
    {

        abort_unless(
            request()->user()->can('attendance.manage'),
            403
        );


        $today = now()->toDateString();



        $attendance = Attendance::firstOrCreate([

            'employee_id'=>$employee->id,

            'work_date'=>$today,

        ]);



        if($attendance->check_in){

            return back()->with(

                'error',

                'ورود قبلاً ثبت شده است'

            );

        }



        $employee->load('shift');


        $checkIn = now();



        $times = $this->calculateTimes(

            $employee,

            $today,

            $checkIn->format('H:i'),

            null

        );



        $attendance->update([

            'check_in'=>$checkIn,

            ...$times

        ]);



        return back()->with(

            'success',

            'ورود ثبت شد'

        );

    }









    public function checkOut(Employee $employee)
    {


        abort_unless(
            request()->user()->can('attendance.manage'),
            403
        );




        $today = now()->toDateString();






        $attendance = Attendance::where(

            'employee_id',

            $employee->id

        )

        ->whereDate(

            'work_date',

            $today

        )

        ->first();








        if(!$attendance || !$attendance->check_in){


            return back()->with(

                'error',

                'ابتدا ورود را ثبت کنید'

            );

        }







        $employee->load('shift');



        $checkOut = now();





        $times = $this->calculateTimes(

            $employee,

            $today,

            $attendance->check_in->format('H:i'),

            $checkOut->format('H:i')

        );








        $attendance->update([


            'check_out'=>$checkOut,


            ...$times


        ]);







        return back()->with(

            'success',

            'خروج ثبت شد'

        );


    }









    private function calculateTimes(

        Employee $employee,

        $date,

        $checkIn,

        $checkOut

    ){



        $workedMinutes = 0;

        $lateMinutes = 0;

        $overtimeMinutes = 0;







        if(!$employee->shift){

        if($checkOut){

            $start = strtotime(
                $date.' '.$checkIn
            );


            $end = strtotime(
                $date.' '.$checkOut
            );


            return [

                'worked_minutes'=>max(
                    0,
                    intval(($end-$start)/60)
                ),

                'late_minutes'=>0,

                'overtime_minutes'=>0,

            ];

        }


        return [

            'worked_minutes'=>0,

            'late_minutes'=>0,

            'overtime_minutes'=>0,

        ];

    }








        $shiftStart = strtotime(

            $date.' '.$employee->shift->start_time

        );






        $shiftEnd = strtotime(

            $date.' '.$employee->shift->end_time

        );








        $start = strtotime(

            $date.' '.$checkIn

        );








        if($start > $shiftStart){


            $lateMinutes = intval(

                ($start-$shiftStart)/60

            );


        }









        if($checkOut){


            $end = strtotime(

                $date.' '.$checkOut

            );




            if($end > $start){


                $workedMinutes = intval(

                    ($end-$start)/60

                );

            }







            $standardMinutes = intval(

                ($shiftEnd-$shiftStart)/60

            );






            if($workedMinutes > $standardMinutes){


                $overtimeMinutes =

                    $workedMinutes - $standardMinutes;


            }


        }









        return [

            'worked_minutes'=>$workedMinutes,

            'late_minutes'=>$lateMinutes,

            'overtime_minutes'=>$overtimeMinutes,

        ];

    }

}