<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Status;
use Illuminate\Support\Str;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $activeStatus = Status::where('name', 'publish')->first();
       $statusId = $activeStatus ? $activeStatus->id : 1;

       $categories = [
            'Blogs',
            'Events',
            'News',
            'Technology',
            'Education',
            'Announcements'
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat)], // Agar slug pehle se hai to update karega warna naya banayega
                [
                    'name' => $cat,
                    'description' => "This is the category for " . $cat,
                    'status_id' => $statusId,
                ]
            );
        }

    }
}
