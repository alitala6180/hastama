<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;



class StoreEmployeeRequest extends FormRequest
{


    public function authorize(): bool
    {

        return $this->user()?->can('employees.create') ?? false;

    }





    public function rules(): array
    {

        return [

            'employee_code'=>[

                'required',

                'string',

                'max:50',

                'unique:employees,employee_code',

            ],



            'first_name'=>[

                'required',

                'string',

                'max:100',

            ],



            'last_name'=>[

                'required',

                'string',

                'max:100',

            ],



            'national_code'=>[

                'nullable',

                'string',

                'max:10',

                'unique:employees,national_code',

            ],



            'department_id'=>[

                'required',

                'exists:departments,id',

            ],



            'position_id'=>[

                'required',

                'exists:positions,id',

            ],




            'shift_id'=>[

                'nullable',

                'exists:shifts,id',

            ],




            'mobile'=>[

                'nullable',

                'string',

                'max:20',

            ],



            'hire_date'=>[

                'nullable',

                'date',

            ],



            'status'=>[

                'required',

                'in:active,inactive',

            ],


        ];

    }


}