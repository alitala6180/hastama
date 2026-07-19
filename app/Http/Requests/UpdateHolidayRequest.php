<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Models\Holiday;



class UpdateHolidayRequest extends FormRequest
{


    public function authorize(): bool
    {

        return $this->user()?->can('holidays.edit') ?? false;

    }






    public function rules(): array
    {


        /** @var Holiday $holiday */

        $holiday = $this->route('holiday');



        return [




            'title'=>[

                'required',

                'string',

                'max:255',

            ],






            'holiday_date'=>[

                'required',

                'date',

                Rule::unique(
                    'holidays',
                    'holiday_date'
                )
                ->ignore($holiday),


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