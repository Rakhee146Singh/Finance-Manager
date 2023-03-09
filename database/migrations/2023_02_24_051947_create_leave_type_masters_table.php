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
        Schema::create('leave_type_masters', function (Blueprint $table) {
            $table->uuid('id', 36)->primary();
            $table->string('name', 251);
            $table->boolean('is_default')->default(true)->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->char('created_by', 36)->nullable();
            $table->char('updated_by', 36)->nullable();
            $table->char('deleted_by', 36)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_type_masters');
    }
};
