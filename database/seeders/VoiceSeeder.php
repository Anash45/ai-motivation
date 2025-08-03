<?php

namespace Database\Seeders;

use App\Models\Voice;
use Illuminate\Database\Seeder;

class VoiceSeeder extends Seeder
{
    public function run()
    {
        Voice::insert([
            ['name' => 'Nova', 'gender' => 'female', 'model_id' => 'nova'],
            ['name' => 'Onyx', 'gender' => 'male', 'model_id' => 'onyx'],
        ]);
    }
}
