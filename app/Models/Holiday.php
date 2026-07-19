<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Holiday extends Model
{

    use HasFactory;



    protected $fillable = [

        'title',

        'holiday_date',

        'type',

        'description',

        'is_active',

    ];




    protected $casts = [

        'holiday_date'=>'date',

        'is_active'=>'boolean',

    ];





    public function scopeActive($query)
    {

        return $query->where(
            'is_active',
            true
        );

    }



}