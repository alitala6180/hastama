<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
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



        $attendances = Attendance::with([

            'employee:id,first_name,last_name,employee_code'

        ])

        ->whereBetween(

            'work_date',

            [
                $from,
                $to
            ]

        )


        ->when(
            $employeeId,
            function($query,$employeeId){

                $query->where(
                    'employee_id',
                    $employeeId
                );

            }
        )


        ->orderBy(
            'work_date',
            'desc'
        )


        ->paginate(20)

        ->withQueryString();





        $summary = [

            'worked_minutes'=>
                Attendance::whereBetween(

                    'work_date',

                    [
                        $from,
                        $to
                    ]

                )

                ->when(
                    $employeeId,
                    function($query,$employeeId){

                        $query->where(
                            'employee_id',
                            $employeeId
                        );

                    }
                )

                ->sum(
                    'worked_minutes'
                ),




            'late_minutes'=>
                Attendance::whereBetween(

                    'work_date',

                    [
                        $from,
                        $to
                    ]

                )

                ->when(
                    $employeeId,
                    function($query,$employeeId){

                        $query->where(
                            'employee_id',
                            $employeeId
                        );

                    }
                )

                ->sum(
                    'late_minutes'
                ),





            'overtime_minutes'=>
                Attendance::whereBetween(

                    'work_date',

                    [
                        $from,
                        $to
                    ]

                )

                ->when(
                    $employeeId,
                    function($query,$employeeId){

                        $query->where(
                            'employee_id',
                            $employeeId
                        );

                    }
                )

                ->sum(
                    'overtime_minutes'
                ),

        ];





        return Inertia::render(

            'Attendances/Report',

            [

                'attendances'=>$attendances,


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

                ->get(),



                'filters'=>[

                    'from'=>$from,

                    'to'=>$to,

                    'employee_id'=>$employeeId

                ],



                'summary'=>$summary


            ]

        );

    }


}