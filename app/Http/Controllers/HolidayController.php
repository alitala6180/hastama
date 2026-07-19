<?php

namespace App\Http\Controllers;


use App\Models\Holiday;

use App\Http\Requests\StoreHolidayRequest;
use App\Http\Requests\UpdateHolidayRequest;

use Illuminate\Http\Request;

use Inertia\Inertia;



class HolidayController extends Controller
{



    public function index(Request $request)
    {


        abort_unless(
            $request->user()->can('holidays.view'),
            403
        );



        $holidays = Holiday::query()



            ->when(
                $request->search,
                function($query,$search){

                    $query->where(
                        'title',
                        'like',
                        "%{$search}%"
                    );

                }
            )



            ->latest('holiday_date')

            ->paginate(10)

            ->withQueryString();





        return Inertia::render(

            'Holidays/Index',

            [

                'holidays'=>$holidays,


                'filters'=>[

                    'search'=>$request->search

                ]

            ]

        );


    }









    public function create()
    {


        abort_unless(
            request()->user()->can('holidays.create'),
            403
        );



        return Inertia::render(

            'Holidays/Create'

        );


    }









    public function store(StoreHolidayRequest $request)
    {


        Holiday::create(

            $request->validated()

        );



        return redirect()

            ->route('holidays.index')

            ->with(

                'success',

                'تعطیلی با موفقیت ثبت شد'

            );


    }









    public function show(Holiday $holiday)
    {


        return Inertia::render(

            'Holidays/Show',

            [

                'holiday'=>$holiday

            ]

        );


    }









    public function edit(Holiday $holiday)
    {


        abort_unless(
            request()->user()->can('holidays.edit'),
            403
        );



        return Inertia::render(

            'Holidays/Edit',

            [

                'holiday'=>$holiday

            ]

        );


    }









    public function update(
        UpdateHolidayRequest $request,
        Holiday $holiday
    )
    {


        $holiday->update(

            $request->validated()

        );



        return redirect()

            ->route('holidays.index')

            ->with(

                'success',

                'تعطیلی بروزرسانی شد'

            );


    }









    public function destroy(Holiday $holiday)
    {


        abort_unless(
            request()->user()->can('holidays.delete'),
            403
        );



        $holiday->delete();



        return redirect()

            ->route('holidays.index')

            ->with(

                'success',

                'تعطیلی حذف شد'

            );


    }



}