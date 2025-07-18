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
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('enrollment_date');
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('parent_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('student_id_number')->unique();
            $table->string('tutor_phone_number')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
