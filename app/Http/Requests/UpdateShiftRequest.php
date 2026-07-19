<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateShiftRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }




    public function rules(): array
    {
        return [

            'name' => [
                'required',
                'string',
                'max:100',
            ],



            'start_time' => [
                'required',
                'date_format:H:i',
            ],



            'end_time' => [
                'required',
                'date_format:H:i',
            ],



            'description' => [
                'nullable',
                'string',
                'max:500',
            ],



            'is_active' => [
                'required',
                'boolean',
            ],

        ];
    }

}