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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
			$table->string('vehicle_license_plate',50);
			$table->date('date_rent_start');			
			$table->date('date_rent_end');			
			$table->string('address_buyer');			
			$table->string('address_name');						
			$table->string('address_phone');						
			$table->integer('years_order');						
			$table->integer('months_order');									
			$table->integer('weeks_order');						
			$table->integer('days_order');			
			$table->bigInteger('total_of_order');						
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
