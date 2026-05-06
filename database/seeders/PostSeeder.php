<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Category;
use App\Models\Status;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Support\Str;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();
        $category = Category::first() ?? Category::create(['name' => 'Technology', 'slug' => 'technology', 'status_id' => 1]);
        $status = Status::where('name', 'publish')->first();

        // 1. Manual Post Example
        Post::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'status_id' => $status->id,
            'title' => 'My First Blog Post',
            'slug' => Str::slug('My First Blog Post'),
            'content' => 'This is the content of my very first blog post.',
            'excerpt' => 'Short summary of the post...',
            'published_at' => now(),
        ]);

        // 2. Factory ka istemal (Agar aapne PostFactory banayi hai)
        // Post::factory(10)->create();
    }
}
