<?php

namespace App\Http\Controllers;


use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Shift;
use App\Models\Holiday;

use Illuminate\Http\Request;

use Inertia\Inertia;



class AttendanceReportController extends Controller
{


    public function index(Request $request)
    {


        $from = $request->from
            ?? now()->startOfMonth()->toDateString();



        $to = $request->to
            ?? now()->endOfMonth()->toDateString();




        $employeeId = $request->employee_id;


        $departmentId = $request->department_id;


        $shiftId = $request->shift_id;





        $query = Attendance::with([


            'employee:id,first_name,last_name,employee_code,department_id,shift_id',


            'employee.department:id,name',


            'employee.shift:id,name,start_time,end_time'


        ])

        ->whereBetween(

            'work_date',

            [

                $from,

                $to

            ]

        );






        $query->when(

            $employeeId,

            function($q) use ($employeeId){

                $q->where(

                    'employee_id',

                    $employeeId

                );

            }

        );







        $query->when(

            $departmentId,

            function($q) use ($departmentId){

                $q->whereHas(

                    'employee',

                    function($employee) use ($departmentId){

                        $employee->where(

                            'department_id',

                            $departmentId

                        );

                    }

                );

            }

        );








        $query->when(

            $shiftId,

            function($q) use ($shiftId){

                $q->whereHas(

                    'employee',

                    function($employee) use ($shiftId){

                        $employee->where(

                            'shift_id',

                            $shiftId

                        );

                    }

                );

            }

        );







        $attendances = $query

            ->orderBy(

                'work_date',

                'desc'

            )

            ->paginate(20)

            ->withQueryString();









        $summaryQuery = Attendance::whereBetween(

            'work_date',

            [

                $from,

                $to

            ]

        );






        if($employeeId){

            $summaryQuery->where(

                'employee_id',

                $employeeId

            );

        }







        $summary = [


            'worked_minutes'=>

                (clone $summaryQuery)

                ->sum(

                    'worked_minutes'

                ),



            'late_minutes'=>

                (clone $summaryQuery)

                ->sum(

                    'late_minutes'

                ),



            'overtime_minutes'=>

                (clone $summaryQuery)

                ->sum(

                    'overtime_minutes'

                ),



            'days'=>

                (clone $summaryQuery)

                ->count(),



        ];









        $employeesQuery = Employee::where(

            'status',

            'active'

        )

        ->with([

            'department:id,name',

            'shift:id,name,start_time,end_time'

        ]);







        $employeesQuery->when(

            $employeeId,

            function($q) use ($employeeId){

                $q->where(

                    'id',

                    $employeeId

                );

            }

        );







        $employeesQuery->when(

            $departmentId,

            function($q) use ($departmentId){

                $q->where(

                    'department_id',

                    $departmentId

                );

            }

        );







        $employeesQuery->when(

            $shiftId,

            function($q) use ($shiftId){

                $q->where(

                    'shift_id',

                    $shiftId

                );

            }

        );







        $employees = $employeesQuery->get();










        $holidayDates = Holiday::active()

            ->whereBetween(

                'holiday_date',

                [

                    $from,

                    $to

                ]

            )

            ->pluck(

                'holiday_date'

            )

            ->map(

                fn($date)=>$date->format('Y-m-d')

            );









        $employeeSummaries = $employees->map(function($employee) use (

            $from,

            $to,

            $holidayDates

        ){



            $records = Attendance::where(

                'employee_id',

                $employee->id

            )

            ->whereBetween(

                'work_date',

                [

                    $from,

                    $to

                ]

            )

            ->get();







            $totalDays = collect(

                \Carbon\CarbonPeriod::create(

                    $from,

                    $to

                )

            )

            ->filter(function($date) use ($holidayDates){


                return !$holidayDates->contains(

                    $date->format('Y-m-d')

                );


            })

            ->count();








            return [


                'employee'=>$employee,



                'attendance_days'=>$records->count(),



                'absent_days'=>

                    max(

                        $totalDays - $records->count(),

                        0

                    ),



                'worked_minutes'=>

                    $records->sum(

                        'worked_minutes'

                    ),



                'late_minutes'=>

                    $records->sum(

                        'late_minutes'

                    ),



                'overtime_minutes'=>

                    $records->sum(

                        'overtime_minutes'

                    ),



            ];



        });









        return Inertia::render(

            'Attendances/Report',

            [


                'attendances'=>$attendances,



                'employees'=>$employees,



                'departments'=>Department::where(

                    'is_active',

                    true

                )

                ->select(

                    'id',

                    'name'

                )

                ->get(),




                'shifts'=>Shift::where(

                    'is_active',

                    true

                )

                ->select(

                    'id',

                    'name'

                )

                ->get(),





                'employeeSummaries'=>$employeeSummaries,




                'summary'=>$summary,





                'filters'=>[


                    'from'=>$from,


                    'to'=>$to,


                    'employee_id'=>$employeeId,


                    'department_id'=>$departmentId,


                    'shift_id'=>$shiftId,


                ]


            ]

        );


    }


}