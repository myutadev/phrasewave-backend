<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $params = [
            'name' => 'yuta',
            'email' => 'yuta@test.co.jp',
            'password' => Hash::make('1111'), // パスワードをハッシュ化
            'email_verified_at' => now(), // メール確認日時
            'remember_token' => Str::random(10), // remember_tokenを生成
        ];

        DB::table('users')->insert($params);
    }
}
