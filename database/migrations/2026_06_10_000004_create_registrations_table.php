<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('citizen_id');
            $table->unsignedBigInteger('program_id');
            $table->timestamp('registered_at');
            $table->timestamp('completed_at')->nullable();
            $table->integer('progress_percent')->default(0);

            $table->foreign('citizen_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->unique(['citizen_id', 'program_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
