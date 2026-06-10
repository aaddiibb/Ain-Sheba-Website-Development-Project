<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lawyer_id');
            $table->unsignedBigInteger('legal_area_id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->string('thumbnail')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->enum('level', ['basic', 'intermediate', 'advanced'])->default('basic');
            $table->string('language')->default('Bengali');
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->timestamps();

            $table->foreign('lawyer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('legal_area_id')->references('id')->on('legal_areas')->onDelete('restrict');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
