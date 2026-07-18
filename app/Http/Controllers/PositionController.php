<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PositionController extends Controller
{

    public function index(Request $request)
    {

        $positions = Position::query()

            ->with('department:id,name')

            ->when($request->search, function ($query, $search) {

                $query->where('name', 'like', "%{$search}%");

            })

            ->select([
                'id',
                'department_id',
                'name',
                'description',
                'is_active',
                'created_at',
            ])

            ->latest()

            ->paginate(10)

            ->withQueryString();



        return Inertia::render('Positions/Index', [

            'positions' => $positions,

            'filters' => [
                'search' => $request->search,
            ],

        ]);

    }



    public function create()
    {

        return Inertia::render('Positions/Create', [

            'departments' => Department::where('is_active', true)
                ->select('id','name')
                ->get(),

        ]);

    }




    public function store(Request $request)
    {
        abort_unless($request->user()->can('positions.manage'), 403);

        $validated = $request->validate([

            'department_id' => [
                'required',
                'exists:departments,id'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'description' => [
                'nullable',
                'string'
            ],

            'is_active' => [
                'boolean'
            ],

        ]);



        Position::create($validated);



        return redirect()
            ->route('positions.index')
            ->with('success','سمت شغلی با موفقیت ثبت شد');

    }





    public function show(Position $position)
    {

        $position->load('department');


        return Inertia::render('Positions/Show',[

            'position'=>$position

        ]);

    }





    public function edit(Position $position)
    {

        return Inertia::render('Positions/Edit',[

            'position'=>$position,


            'departments'=>Department::where('is_active',true)
                ->select('id','name')
                ->get(),

        ]);

    }





    public function update(Request $request, Position $position)
    {
        abort_unless($request->user()->can('positions.manage'), 403);


        $validated=$request->validate([

            'department_id'=>[
                'required',
                'exists:departments,id'
            ],

            'name'=>[
                'required',
                'string',
                'max:255'
            ],

            'description'=>[
                'nullable',
                'string'
            ],

            'is_active'=>[
                'boolean'
            ],

        ]);



        $position->update($validated);



        return redirect()
            ->route('positions.index')
            ->with('success','سمت شغلی بروزرسانی شد');


    }





    public function destroy(Position $position)
    {
        abort_unless(request()->user()->can('positions.manage'), 403);
        if ($position->employees()->count() > 0) {

            return redirect()
                ->route('positions.index')
                ->with('error', 'این سمت دارای پرسنل است و امکان حذف آن وجود ندارد');

        }


        $position->delete();


        return redirect()
            ->route('positions.index')
            ->with('success', 'سمت شغلی با موفقیت حذف شد');

    }

}
