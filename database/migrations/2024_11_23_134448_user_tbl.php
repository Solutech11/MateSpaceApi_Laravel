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
        //
        Schema::create('tbl_users',function(Blueprint $table){
            $table->id(); // Auto-incrementing primary key
            $table->string('username')->unique(); // Unique username
            $table->string('email')->unique(); // Unique email
            $table->string('password'); // Store hashed password
            $table->boolean('other_details')->default(false); // Boolean, default is false
            $table->boolean('answer_qa')->default(false); // Boolean, default is false
            $table->boolean('visible')->default(false); // Boolean, default is false
            $table->boolean('verified')->default(false); // Boolean, default is false
            $table->timestamps(); // Adds created_at and updated_at timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('tbl_users');
    }
};
