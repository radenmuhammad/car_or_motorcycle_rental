<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. vehicle license plate
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->string('vehicle_license_plate',50)->primary();						
            $table->string('name_of_items');
            $table->integer('price');			
            $table->boolean('available')->default(1);			
            $table->string('distributor');			
            $table->timestamps();
        });
		DB::table('items')->insert(
			array(
				'vehicle_license_plate' => 'wqwqw121212',
				'name_of_items' => 'Mio/Vega (2004)',
				'price' => '5000000',
				'available' => true,
				'distributor' => 'Hairus',
				'created_at' => '2023-08-11 19:37:00'				
			)
		);				
		DB::table('items')->insert(
			array(
				'vehicle_license_plate' => 'zxzzzczc',
				'name_of_items' => 'Ayla',
				'price' => '135000000',
				'available' => true,
				'distributor' => 'Hairus',
				'created_at' => '2023-08-11 19:37:00'				
			)
		);				
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
