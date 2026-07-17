<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Department;
use App\Models\Position;

class Employee extends Model
{
    use HasFactory;


    protected $fillable = [
        'department_id',
        'position_id',
        'employee_code',
        'first_name',
        'last_name',
        'national_code',
        'mobile',
        'hire_date',
        'is_active',
        'status',
    ];


    protected $casts = [
        'hire_date' => 'date',
        'is_active' => 'boolean',
    ];


    /**
     * ارتباط کارمند با حساب کاربری
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }


    /**
     * واحد سازمانی کارمند
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    /**
     * سمت شغلی کارمند
     */
    public function position()
    {
        return $this->belongsTo(Position::class);
    }


    /**
     * نام کامل کارمند
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}