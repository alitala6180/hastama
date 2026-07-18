<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Shift extends Model
{

    protected $fillable = [

        'name',

        'start_time',

        'end_time',

        'work_minutes',

        'is_active',

    ];





    protected $casts = [

        'is_active' => 'boolean',

    ];





    public function employees()
    {

        return $this->hasMany(
            Employee::class
        );

    }


}