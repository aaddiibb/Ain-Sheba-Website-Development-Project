<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('citizen_id');
            $table->unsignedBigInteger('lawyer_id');
            $table->date('booked_date');
            $table->string('time_slot');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->decimal('fee', 8, 2)->default(0);
            $table->text('citizen_notes')->nullable();
            $table->text('lawyer_response')->nullable();
            $table->timestamps();

            $table->foreign('citizen_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('lawyer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
