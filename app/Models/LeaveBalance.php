<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class LeaveBalance extends Model
{

    use HasFactory;



    protected $fillable = [

        'employee_id',

        'year',

        'annual_days',

        'used_days',

    ];




    public function employee()
    {

        return $this->belongsTo(
            Employee::class
        );

    }




    public function getRemainingDaysAttribute()
    {

        return $this->annual_days - $this->used_days;

    }


}