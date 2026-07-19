<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;


class Leave extends Model
{

    use HasFactory;



    protected $fillable = [

        'employee_id',

        'type',

        'from_date',

        'to_date',

        'days',

        'reason',

        'status',

        'approved_by',

    ];





    protected $casts = [

        'from_date'=>'date',

        'to_date'=>'date',

    ];







    protected static function boot()
    {

        parent::boot();



        static::saving(function($leave){


            if(
                $leave->from_date &&
                $leave->to_date
            ){

                $leave->days =

                    Carbon::parse(
                        $leave->from_date
                    )

                    ->diffInDays(

                        Carbon::parse(
                            $leave->to_date
                        )

                    )

                    + 1;

            }


        });


    }









    public function employee()
    {

        return $this->belongsTo(
            Employee::class
        );

    }









    public function approver()
    {

        return $this->belongsTo(

            User::class,

            'approved_by'

        );

    }









    public function getStatusTextAttribute()
    {

        return match($this->status){

            'pending'=>'در انتظار تایید',

            'approved'=>'تایید شده',

            'rejected'=>'رد شده',

            default=>'-'

        };

    }







    public function getTypeTextAttribute()
    {

        return match($this->type){


            'annual'=>'استحقاقی',

            'sick'=>'استعلاجی',

            'unpaid'=>'بدون حقوق',

            default=>$this->type

        };

    }



}