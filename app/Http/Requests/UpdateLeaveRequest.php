<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;



class UpdateLeaveRequest extends FormRequest
{


    public function authorize(): bool
    {

        return $this->user()?->can('leaves.edit') ?? false;

    }








    public function rules(): array
    {


        return [



            'employee_id'=>[

                'required',

                'exists:employees,id',

            ],






            'type'=>[

                'required',

                'in:annual,sick,hourly,unpaid',

            ],






            'start_date'=>[

                'required',

                'date',

            ],






            'end_date'=>[

                'required',

                'date',

                'after_or_equal:start_date',

            ],






            'days'=>[

                'nullable',

                'numeric',

                'min:0.5',

            ],



            'reason'=>[

                'nullable',

                'string',

                'max:500',

            ],




        ];


    }



}