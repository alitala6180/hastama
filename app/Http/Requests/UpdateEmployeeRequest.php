<?php

namespace App\Http\Requests;


use App\Models\Employee;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;




class UpdateEmployeeRequest extends FormRequest
{


    public function authorize(): bool
    {

        return $this->user()?->can('employees.edit') ?? false;

    }







    public function rules(): array
    {


        /** @var Employee $employee */
        $employee = $this->route('employee');




        return [



            'employee_code'=>[


                'required',

                'string',

                'max:50',


                Rule::unique(
                    'employees',
                    'employee_code'
                )->ignore($employee),


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



                Rule::unique(
                    'employees',
                    'national_code'
                )->ignore($employee),



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