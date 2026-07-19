<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up(): void
    {

        Schema::create('leaves', function (Blueprint $table) {


            $table->id();



            $table->foreignId('employee_id')

                ->constrained()

                ->cascadeOnDelete();




            $table->enum('type',[

                'annual',

                'sick',

                'hourly',

                'unpaid'

            ]);




            $table->date('start_date');

            $table->date('end_date');



            $table->decimal(

                'days',

                5,

                2

            );



            $table->text('reason')

                ->nullable();




            $table->enum('status',[

                'pending',

                'approved',

                'rejected'

            ])

            ->default('pending');





            $table->foreignId('approved_by')

                ->nullable()

                ->constrained('users')

                ->nullOnDelete();




            $table->timestamps();


        });

    }





    public function down(): void
    {

        Schema::dropIfExists('leaves');

    }

};