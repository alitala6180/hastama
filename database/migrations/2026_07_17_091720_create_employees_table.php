<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // اطلاعات هویتی
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('national_code')->unique()->nullable();
            $table->date('birth_date')->nullable();

            // اطلاعات تماس
            $table->string('mobile')->nullable();
            $table->text('address')->nullable();

            // اطلاعات سازمانی
            $table->foreignId('department_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('position_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // وضعیت کاری
            $table->date('hire_date')->nullable();

            $table->enum('status', [
                'active',
                'inactive',
                'terminated'
            ])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
