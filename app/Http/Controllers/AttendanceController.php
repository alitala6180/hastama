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
            'employee_code'
        )

        ->with([

            'attendances'=>function($query) use ($date){

                $query->whereDate(

                    'work_date',

                    $date

                );

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
        abort_unless($request->user()->can('attendance.manage'), 403);

        $validated = $request->validate([

            'employee_id'=>'required|exists:employees,id',

            'work_date'=>'required|date',

            'check_in'=>'required',

            'check_out'=>'nullable',

        ]);

        $request->validate([
            'employee_id' => [Rule::unique('attendances', 'employee_id')->where('work_date', $validated['work_date'])],
        ]);



        $times = $this->calculateTimes(

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





    public function show(Attendance $attendance)
    {

        $attendance->load('employee');


        return Inertia::render(
            'Attendances/Show',
            [

                'attendance'=>$attendance

            ]
        );

    }





    public function edit(Attendance $attendance)
    {

        return Inertia::render(
            'Attendances/Edit',
            [

                'attendance'=>$attendance,

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





    public function update(Request $request, Attendance $attendance)
    {
        abort_unless($request->user()->can('attendance.manage'), 403);

        $validated = $request->validate([

            'employee_id'=>'required|exists:employees,id',

            'work_date'=>'required|date',

            'check_in'=>'required',

            'check_out'=>'nullable',

        ]);

        $request->validate([
            'employee_id' => [Rule::unique('attendances', 'employee_id')->where('work_date', $validated['work_date'])->ignore($attendance)],
        ]);



        $times = $this->calculateTimes(

            $validated['work_date'],

            $validated['check_in'],

            $validated['check_out']

        );



        $attendance->update([

            ...$validated,

            ...$times,

        ]);



        return redirect()

            ->route('attendances.index')

            ->with(
                'success',
                'حضور و غیاب بروزرسانی شد'
            );

    }





    public function destroy(Attendance $attendance)
    {
        abort_unless(request()->user()->can('attendance.manage'), 403);

        $attendance->delete();


        return back()

            ->with(
                'success',
                'رکورد حذف شد'
            );

    }





    public function checkIn(Employee $employee)
    {

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



        $attendance->update([

            'check_in'=>now(),

        ]);



        return back()->with(

            'success',

            'ورود ثبت شد'

        );

    }






    public function checkOut(Employee $employee)
    {

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



        if(!$attendance){


            return back()->with(

                'error',

                'ابتدا ورود را ثبت کنید'

            );

        }



        if(!$attendance->check_in){


            return back()->with(

                'error',

                'ابتدا ورود را ثبت کنید'

            );

        }



        $checkOut = now();



        $times = $this->calculateTimes(

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

        $date,

        $checkIn,

        $checkOut

    ){

        $workedMinutes = 0;

        $lateMinutes = 0;

        $overtimeMinutes = 0;



        if($checkIn){


            $start = strtotime(

                $date.' '.$checkIn

            );


            $officialStart = strtotime(

                $date.' 08:00:00'

            );



            if($start > $officialStart){


                $lateMinutes = intval(

                    ($start-$officialStart)/60

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



                if($workedMinutes > 480){


                    $overtimeMinutes =

                        $workedMinutes - 480;

                }

            }

        }



        return [

            'worked_minutes'=>$workedMinutes,

            'late_minutes'=>$lateMinutes,

            'overtime_minutes'=>$overtimeMinutes,

        ];

    }


}
