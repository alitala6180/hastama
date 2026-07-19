<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class UpdateLeaveRequest extends FormRequest
{


    public function authorize(): bool
    {
        return true;
    }




    public function rules(): array
    {

        return [


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

                'required',

                'numeric',

                'min:0.5',

            ],




            'reason'=>[

                'nullable',

                'string',

            ],


        ];

    }


}