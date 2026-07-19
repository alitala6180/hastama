<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('holidays', function (Blueprint $table) {


            $table->id();


            $table->string('title');


            $table->date('holiday_date');


            $table->enum(
                'type',
                [
                    'official',
                    'weekly',
                    'company'
                ]
            )
            ->default('official');



            $table->text('description')
                ->nullable();



            $table->boolean('is_active')
                ->default(true);



            $table->timestamps();



            $table->unique('holiday_date');


        });

    }



    public function down(): void
    {

        Schema::dropIfExists('holidays');

    }

};