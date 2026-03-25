<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Status;
use Illuminate\Database\Seeder;

class StatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $data = [['name' => 'draft'],['name'=>'publish'],['name'=>'archived']];
         foreach($data as $status)
         {
              Status::create($status);
         }

    }
}
