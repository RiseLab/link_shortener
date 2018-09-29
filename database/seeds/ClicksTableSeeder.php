<?php

use Illuminate\Database\Seeder;
use App\Click;

class ClicksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$referers = [
    		null,
    	    'https://google.ru/',
		    'https://yandex.ru/',
		    'https://riselab.ru/',
	    ];

        Click::truncate();

		$faker = \Faker\Factory::create();

		for ($i = 0; $i < 200; $i++) {
			Click::create([
				'shortlink_id' => rand(1, 9),
				'referer' => $referers[rand(0, count($referers) - 1)],
				'created_at' => $faker->dateTimeBetween('-10 days'),
			]);
		}
    }
}
