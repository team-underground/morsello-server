<?php

use App\Bit;
use App\Category;
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
        $this->call(CategoriesTableSeeder::class);
        factory(User::class, 10)->create();
        factory(Bit::class, 500)->create();

        $userIds = User::pluck('id')->all();
        $categoryIds = Category::pluck('id')->all();

        foreach (Bit::cursor() as $key => $bit) {
            $bit->likes()->sync(array_rand($userIds, rand(2, 5)));
            $bit->bookmarks()->sync(array_rand($userIds, rand(2, 5)));
            $bit->tags()->sync(array_rand($categoryIds, rand(2, 5)));
        }
    }
}
