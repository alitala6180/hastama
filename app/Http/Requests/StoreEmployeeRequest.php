<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [

            'employee_code' => [
                'required',
                'string',
                'unique:employees,employee_code',
                'max:50',
            ],


            'first_name' => [
                'required',
                'string',
                'max:100',
            ],


            'last_name' => [
                'required',
                'string',
                'max:100',
            ],


            'national_code' => [
                'nullable',
                'string',
                'max:10',
                'unique:employees,national_code',
            ],


            'department_id' => [
                'required',
                'exists:departments,id',
            ],


            'position_id' => [
                'required',
                'exists:positions,id',
            ],


            'mobile' => [
                'nullable',
                'string',
                'max:20',
            ],


            'hire_date' => [
                'nullable',
                'date',
            ],


            'status' => [
                'nullable',
                'in:active,inactive',
            ],

        ];
    }
}
