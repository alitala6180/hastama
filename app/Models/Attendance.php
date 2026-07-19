<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Holiday;


class Attendance extends Model
{

    use HasFactory;



    protected $fillable = [

        'employee_id',

        'work_date',

        'check_in',

        'check_out',

        'worked_minutes',

        'late_minutes',

        'overtime_minutes',

    ];





    protected $casts = [

        'work_date' => 'date',

        'check_in' => 'datetime',

        'check_out' => 'datetime',

    ];






    /**
     * کارمند مربوط به رکورد حضور و غیاب
     */
    public function employee()
    {

        return $this->belongsTo(
            Employee::class
        );

    }

    public function holiday()
    {

        return $this->belongsTo(
            Holiday::class,
            'work_date',
            'holiday_date'
        );

    }


}