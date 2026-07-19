<?php

namespace App\Http\Controllers;


use App\Models\Shift;

use App\Http\Requests\StoreShiftRequest;
use App\Http\Requests\UpdateShiftRequest;

use Inertia\Inertia;



class ShiftController extends Controller
{


    public function index()
    {


        $shifts = Shift::query()

            ->latest()

            ->paginate(10)

            ->withQueryString();




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









    public function store(StoreShiftRequest $request)
    {


        abort_unless(
            $request->user()->can('shifts.create'),
            403
        );



        Shift::create(

            $request->validated()

        );



        return redirect()

            ->route('shifts.index')

            ->with(
                'success',
                'شیفت با موفقیت ثبت شد'
            );


    }









    public function show(Shift $shift)
    {


        $shift->loadCount('employees');



        return Inertia::render(
            'Shifts/Show',
            [

                'shift'=>$shift

            ]
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









    public function update(
        UpdateShiftRequest $request,
        Shift $shift
    )
    {


        abort_unless(
            $request->user()->can('shifts.edit'),
            403
        );



        $shift->update(

            $request->validated()

        );



        return redirect()

            ->route('shifts.index')

            ->with(
                'success',
                'شیفت بروزرسانی شد'
            );


    }









    public function destroy(Shift $shift)
    {


        abort_unless(
            request()->user()->can('shifts.delete'),
            403
        );





        if(
            $shift->employees()->exists()
        ){

            return back()

                ->with(
                    'error',
                    'این شیفت به پرسنل اختصاص داده شده و قابل حذف نیست'
                );

        }






        $shift->delete();





        return redirect()

            ->route('shifts.index')

            ->with(
                'success',
                'شیفت حذف شد'
            );


    }



}