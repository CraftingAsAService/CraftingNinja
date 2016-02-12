<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserTableSeeder::class);
        $this->call(LanguageTableSeeder::class);
        $this->call(GamesTableSeeder::class);
    }
}

class UserTableSeeder extends Seeder {

    public function run()
    {
        if ( ! DB::table('users')->where('username', 'Tickthokk')->count())
            \App\User::create(['email' => 'tickthokk@gmail.com', 'username' => 'Tickthokk', 'password' => bcrypt(env('SEEDER_PASSWORD', 'password'))]);
    }

}

class LanguageTableSeeder extends Seeder {

    public function run()
    {
        // Supported Languages
        foreach (array_keys(config('languages')) as $lang)
            if ( ! DB::table('languages')->where('code', $lang)->count())
                DB::table('languages')->insert(['code' => $lang]);
    }

}

class GamesTableSeeder extends Seeder {

    public function run()
    {
        // Create the base of the game
        // Another Seeder will handle data
        $this->create_game('ffxiv', 'Final Fantasy XIV');
        $this->create_game('wow', 'World of Warcraft', 'gsc');
        $this->create_game('ffex', 'Final Fantasy Explorers');
        $this->create_game('rf4', 'Rune Factory 4');
    }

    public function create_game($slug, $name, $currency_type = 'yen', $locale = 'en')
    {
        if (DB::table('games')->where('slug', $slug)->count())
            return;

        $id = DB::table('games')->insertGetId([
            'slug' => $slug,
            'version' => 0,
            'currency_type' => config('currency.' . $currency_type),
        ]);

        $locale_id = DB::table('languages')->where('code', $locale)->first()->id;

        DB::table('game_translations')->insert([
            'language_id' => $locale_id,
            'game_id' => $id,
            'name' => $name,
            'abbreviation' => $slug,
        ]);
    }

}