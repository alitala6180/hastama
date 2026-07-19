<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;



class StoreHolidayRequest extends FormRequest
{


    public function authorize(): bool
    {

        return $this->user()?->can('holidays.create') ?? false;

    }





    public function rules(): array
    {

        return [


            'title'=>[

                'required',

                'string',

                'max:255',

            ],




            'holiday_date'=>[

                'required',

                'date',

                'unique:holidays,holiday_date',

            ],





            'type'=>[

                'required',

                'in:official,weekly,company',

            ],






            'description'=>[

                'nullable',

                'string',

            ],





            'is_active'=>[

                'boolean',

            ],



        ];

    }



}