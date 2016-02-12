<?php

use Illuminate\Database\Seeder;

use DB;

class FakeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(FakeGamesTableSeeder::class);
        $this->call(FakeUserTableSeeder::class);
    }
}

class FakeUserTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();
        $faker->addProvider(new Faker\Provider\Internet($faker));
        // DB::table('users')->delete();

        foreach (range(1, 10) as $i)
            \App\User::create(['email' => $faker->unique()->email(), 'username' => $faker->unique()->userName()]);
    }

}

class FakeGamesTableSeeder extends Seeder {

    public function run()
    {
        $faker = Faker\Factory::create();
        $faker->addProvider(new Faker\Provider\Lorem($faker));

        foreach (range(1, 10) as $i)
        {
        	$id = DB::table('games')->insertGetId([
        		'slug' => 'ffxiv',
        		'version' => '3.15',

        	]);
            \App\Models\Game::create(['email' => $faker->unique()->email(), 'username' => $faker->unique()->userName()]);
        }
    }

}