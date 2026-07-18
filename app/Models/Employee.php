<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Models\User;
use App\Models\Department;
use App\Models\Position;
use App\Models\Shift;
use App\Models\Leave;
use App\Models\Attendance;


class Employee extends Model
{

    use HasFactory;



    protected $fillable = [

        'department_id',

        'position_id',

        'shift_id',

        'employee_code',

        'first_name',

        'last_name',

        'national_code',

        'mobile',

        'hire_date',

        'status',

    ];





    protected $casts = [

        'hire_date' => 'date',

    ];






    /**
     * ارتباط کارمند با حساب کاربری
     */
    public function user()
    {

        return $this->hasOne(
            User::class
        );

    }







    /**
     * واحد سازمانی
     */
    public function department()
    {

        return $this->belongsTo(
            Department::class
        );

    }







    /**
     * سمت شغلی
     */
    public function position()
    {

        return $this->belongsTo(
            Position::class
        );

    }







    /**
     * شیفت کاری
     */
    public function shift()
    {

        return $this->belongsTo(
            Shift::class
        );

    }







    /**
     * حضور و غیاب
     */
    public function attendances()
    {

        return $this->hasMany(
            Attendance::class
        );

    }







    /**
     * درخواست های مرخصی
     */
    public function leaves()
    {

        return $this->hasMany(
            Leave::class
        );

    }







    /**
     * مرخصی های تایید شده سالانه
     */
    public function usedLeaves()
    {

        return $this->hasMany(
                Leave::class
            )

            ->where(
                'status',
                'approved'
            )

            ->where(
                'type',
                'annual'
            );

    }







    /**
     * مانده مرخصی
     */
    public function getRemainingLeaveAttribute()
    {

        return

            ($this->annual_leave ?? 0)

            -

            $this->usedLeaves()->sum('days');

    }







    /**
     * نام کامل
     */
    public function getFullNameAttribute()
    {

        return

            $this->first_name

            .

            ' '

            .

            $this->last_name;

    }


}