<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;


class StoreLeaveRequest extends FormRequest
{

    public function authorize(): bool
    {
        return $this->user()
            ?->can('leave.manage') ?? false;
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


            'from_date'=>[
                'required',
                'date',
            ],


            'to_date'=>[
                'required',
                'date',
                'after_or_equal:from_date',
            ],


            'days'=>[
                'nullable',
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