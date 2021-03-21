<?php
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder{

    public function run()
    {
        $this->call('CoinSeeder');

        // $this->command->info('User table seeded!');
    }

}