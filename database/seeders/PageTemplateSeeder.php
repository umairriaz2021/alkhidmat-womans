<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PageTemplate;

class PageTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'template_name' => 'home_layout', // React logic ke liye
                'display_name'  => 'Home Page Layout', // Dashboard dropdown ke liye
            ],
            [
                'template_name' => 'about_layout',
                'display_name'  => 'About Us Layout',
            ],
            [
                'template_name' => 'services_layout',
                'display_name'  => 'Services Grid Layout',
            ],
            [
                'template_name' => 'contact_layout',
                'display_name'  => 'Contact Form Layout',
            ],
            [
                'template_name' => 'default_layout',
                'display_name'  => 'Generic Content Layout',
            ],
        ];

        foreach ($templates as $template) {
            PageTemplate::updateOrCreate(
                ['template_name' => $template['template_name']], 
                $template
            );
        }
    }
}
