<?php

use Illuminate\Database\Seeder;
use App\ServicesModel;
class Services extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
		//$faker = Faker\Factory::create();
		for ($i=1; $i <= 20; $i++) { 
	        ServicesModel::create([
	            'title' => 'serivce '.Str::random(5),
	            'price' => rand(10,100)
	        ]);
    	}
    }
}
