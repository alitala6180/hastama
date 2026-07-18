<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShiftController extends Controller
{


    public function index()
    {

        $shifts = Shift::latest()
            ->paginate(10);


        return Inertia::render(
            'Shifts/Index',
            [

                'shifts'=>$shifts

            ]
        );

    }




    public function create()
    {

        return Inertia::render(
            'Shifts/Create'
        );

    }





    public function store(Request $request)
    {

        $validated = $request->validate([


            'name'=>'required|string|max:255',


            'start_time'=>'required',


            'end_time'=>'required',


            'description'=>'nullable|string',


            'is_active'=>'boolean',


        ]);



        Shift::create($validated);



        return redirect()

            ->route('shifts.index')

            ->with(
                'success',
                'شیفت با موفقیت ثبت شد'
            );

    }






    public function edit(Shift $shift)
    {


        return Inertia::render(

            'Shifts/Edit',

            [

                'shift'=>$shift

            ]

        );

    }






    public function update(Request $request, Shift $shift)
    {


        $validated = $request->validate([


            'name'=>'required|string|max:255',


            'start_time'=>'required',


            'end_time'=>'required',


            'description'=>'nullable|string',


            'is_active'=>'boolean',


        ]);



        $shift->update($validated);



        return redirect()

            ->route('shifts.index')

            ->with(
                'success',
                'شیفت بروزرسانی شد'
            );

    }






    public function destroy(Shift $shift)
    {

        $shift->delete();



        return redirect()

            ->route('shifts.index')

            ->with(
                'success',
                'شیفت حذف شد'
            );

    }





    public function show(Shift $shift)
    {

        return redirect()

            ->route('shifts.edit',$shift);

    }

}