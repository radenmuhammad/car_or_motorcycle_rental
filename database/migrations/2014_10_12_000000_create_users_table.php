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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username');			
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role');			
            $table->string('verify_key')->nullable();			
            $table->rememberToken();
            $table->timestamps();
        });
		DB::table('users')->insert(
			array(
				'name' => 'Raden Muhammad Anugrah Perdana',
				'username' => '',
				'email' => 'rma18feb@gmail.com',
				'email_verified_at' => '2023-08-11 19:37:00',
				'password' => '$2y$10$kAK8GR8I9.B9wg58FmJx7uYmpzah9Wgq0PdctMn7W9wg2uiLHOLXi',
				'role' => 'Administrator',
				'verify_key' => '',
				'created_at' => '2023-08-11 19:37:00'			
			)
		);	
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
