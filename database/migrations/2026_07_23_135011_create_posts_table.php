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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('job_title', 50);
            $table->string('job_level', 20);
            $table->unsignedSmallInteger('vacancy_count');
            $table->string('employment_type');
            $table->string('salary', 30);
            $table->string('job_location');
            $table->timestamp('deadline');
            $table->string('education_level');
            $table->string('experience');
            $table->string('skills');
            $table->text('specifications');
            $table->unsignedMediumInteger('views')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
