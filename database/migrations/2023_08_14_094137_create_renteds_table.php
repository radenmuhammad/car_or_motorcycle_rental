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
        Schema::create('renteds', function (Blueprint $table) {
			$table->string('name_of_items',50)->primary();
			$table->string('type_of_items');			
            $table->integer('days_price');			
            $table->integer('weeks_price')->comment('(for 7 days)');
            $table->integer('months_price')->comment('(for 30 days)');	
            $table->integer('years_price');			
			$table->string('image')->nullable();						
            $table->timestamps();
        });
		DB::table('renteds')->insert(
			array(
				'name_of_items' => 'Mio/Vega (2004)',
				'type_of_items' => 'Motor',
 				'days_price' => '35000',
				'weeks_price' => '250000',
				'months_price' => '740000',
				'years_price' => '0',				
				'created_at' => '2023-08-11 19:37:00'			
			)
		);
		DB::table('renteds')->insert(
			array(
				'name_of_items' => 'Ayla',
				'type_of_items' => 'Mobil',
 				'days_price' => '135000000',
				'weeks_price' => '0',
				'months_price' => '0',
				'years_price' => '0',				
				'created_at' => '2023-08-11 19:37:00'			
			)
		);		
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renteds');
    }
};
