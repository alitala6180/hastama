<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DepartmentController extends Controller
{

    public function index(Request $request)
    {

        $departments = Department::query()

            ->when($request->search, function ($query, $search) {

                $query->where(function ($q) use ($search) {

                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%");

                });

            })


            ->select([
                'id',
                'name',
                'code',
                'description',
                'is_active',
                'created_at'
            ])


            ->latest()

            ->paginate(10)

            ->withQueryString();



        return Inertia::render('Departments/Index', [

            'departments' => $departments,


            'filters' => [
                'search' => $request->search,
            ]

        ]);

    }



    public function create()
    {

        return Inertia::render('Departments/Create');

    }



    public function store(Request $request)
    {
        abort_unless($request->user()->can('departments.manage'), 403);

        $validated = $request->validate([

            'name' => [
                'required',
                'string',
                'max:255'
            ],


            'code' => [
                'required',
                'string',
                'max:50',
                'unique:departments,code'
            ],


            'description' => [
                'nullable',
                'string'
            ],


            'is_active' => [
                'boolean'
            ],

        ]);



        Department::create($validated);



        return redirect()

            ->route('departments.index')

            ->with(
                'success',
                'واحد سازمانی با موفقیت ثبت شد'
            );

    }




    public function show(Department $department)
    {

        return Inertia::render('Departments/Show', [

            'department' => $department

        ]);

    }




    public function edit(Department $department)
    {

        return Inertia::render('Departments/Edit', [

            'department' => $department

        ]);

    }





    public function update(Request $request, Department $department)
    {
        abort_unless($request->user()->can('departments.manage'), 403);


        $validated = $request->validate([


            'name' => [
                'required',
                'string',
                'max:255'
            ],


            'code' => [
                'required',
                'string',
                'max:50',
                'unique:departments,code,' . $department->id
            ],


            'description' => [
                'nullable',
                'string'
            ],


            'is_active' => [
                'boolean'
            ],


        ]);



        $department->update($validated);



        return redirect()

            ->route('departments.index')

            ->with(
                'success',
                'واحد سازمانی بروزرسانی شد'
            );


    }





    public function destroy(Department $department)
    {
        abort_unless(request()->user()->can('departments.manage'), 403);


        if($department->employees()->exists())
        {

            return back()

                ->with(
                    'error',
                    'این واحد دارای پرسنل است و قابل حذف نیست'
                );

        }



        $department->delete();



        return redirect()

            ->route('departments.index')

            ->with(
                'success',
                'واحد سازمانی حذف شد'
            );


    }


}
