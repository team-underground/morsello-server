<?php

use App\Bit;
use App\Like;
use App\Reply;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        // factory(User::class, 10)->create();
        // factory(Bit::class, 50)->create();
        // factory(Reply::class, 50)->create();
        factory(Like::class, 50)->create();
    }
}
