<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $params = [
            ['language_code' => 'en-US', 'name' => 'English (US)'],
            ['language_code' => 'en-GB', 'name' => 'English (British)'],
            ['language_code' => 'zh-CN', 'name' => 'Chinese (Simplified)'],
            ['language_code' => 'zh-TW', 'name' => 'Chinese (Traditional)'],
            ['language_code' => 'ja', 'name' => 'Japanese'],
            ['language_code' => 'ko', 'name' => 'Korean'],
            ['language_code' => 'es', 'name' => 'Spanish'],
            ['language_code' => 'fr', 'name' => 'French'],
            ['language_code' => 'pt', 'name' => 'Portuguese'],
            ['language_code' => 'de', 'name' => 'German'],
            ['language_code' => 'it', 'name' => 'Italian']
        ];

        DB::table('languages')->insert($params);
    }
}
