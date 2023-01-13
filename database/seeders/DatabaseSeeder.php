<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Article;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        //borrar y crear las carpetas para Article y Category de las factories
        Storage::deleteDirectory('articles');
        Storage::deleteDirectory('categories');

        Storage::makeDirectory('articles');
        Storage::makeDirectory('categories');

        //Llamar al seeder: UserSeeder
        $this->call(UserSeeder::class);

        //Llamar al factories
        Category::factory(22)->create();
        Article::factory(12)->create();
        Comment::factory(22)->create();
    }
}
