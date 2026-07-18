<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use App\Models\Shift;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

use Illuminate\Http\Request;
use Inertia\Inertia;


class EmployeeController extends Controller
{


    public function index(Request $request)
    {

        $employees = Employee::query()

            ->with([

                'department:id,name',

                'position:id,name',

                'shift:id,name',

            ])



            ->when($request->search, function ($query, $search) {


                $query->where(function ($q) use ($search) {


                    $q->where('first_name', 'like', "%{$search}%")

                        ->orWhere('last_name', 'like', "%{$search}%")

                        ->orWhere('employee_code', 'like', "%{$search}%")

                        ->orWhere('national_code', 'like', "%{$search}%");


                });


            })



            ->select([

                'id',

                'employee_code',

                'first_name',

                'last_name',

                'national_code',

                'department_id',

                'position_id',

                'shift_id',

                'status',

                'created_at',

            ])



            ->latest()

            ->paginate(10)

            ->withQueryString();




        return Inertia::render('Employees/Index', [


            'employees'=>$employees,


            'filters'=>[

                'search'=>$request->search,

            ],


        ]);

    }






    public function create()
    {

        return Inertia::render('Employees/Create', [



            'departments'=>Department::where('is_active',true)

                ->select('id','name')

                ->get(),




            'positions'=>Position::where('is_active',true)

                ->select('id','name')

                ->get(),




            'shifts'=>Shift::where('is_active',true)

                ->select(

                    'id',

                    'name',

                    'start_time',

                    'end_time'

                )

                ->get(),


        ]);

    }






    public function store(StoreEmployeeRequest $request)
    {

        abort_unless(
            $request->user()->can('employees.create'),
            403
        );



        Employee::create(

            $request->validated()

        );



        return redirect()

            ->route('employees.index')

            ->with(
                'success',
                'پرسنل با موفقیت ثبت شد'
            );

    }








    public function show(Employee $employee)
    {


        $employee->load([

            'department',

            'position',

            'shift',

        ]);



        return Inertia::render('Employees/Show',[


            'employee'=>$employee


        ]);


    }







    public function edit(Employee $employee)
    {

        return Inertia::render('Employees/Edit',[



            'employee'=>$employee,




            'departments'=>Department::where('is_active',true)

                ->select('id','name')

                ->get(),





            'positions'=>Position::where('is_active',true)

                ->select('id','name')

                ->get(),





            'shifts'=>Shift::where('is_active',true)

                ->select(

                    'id',

                    'name',

                    'start_time',

                    'end_time'

                )

                ->get(),



        ]);

    }







    public function update(
        UpdateEmployeeRequest $request,
        Employee $employee
    )
    {


        abort_unless(
            $request->user()->can('employees.edit'),
            403
        );



        $employee->update(

            $request->validated()

        );



        return redirect()

            ->route('employees.index')

            ->with(
                'success',
                'اطلاعات پرسنل بروزرسانی شد'
            );


    }








    public function destroy(Employee $employee)
    {


        abort_unless(
            request()->user()->can('employees.delete'),
            403
        );



        $employee->delete();



        return redirect()

            ->route('employees.index')

            ->with(
                'success',
                'پرسنل حذف شد'
            );


    }


}