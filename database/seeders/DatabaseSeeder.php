<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::create([
			'id' => 1,
			'name' => 'João da Silva',
			'type' => 'CLIENT',
			'email' => 'joaodasilva@gmail.com',
			'password' => Hash::make('123123123')
		]);

		\App\Models\Patient::create([
			'id' => 1,
			'user_id' => 1,
			'name' => 'Pingo',
			'breed' => 'Border Collie',
			'gender' => 'M',
			'birthdate' => '2010-10-10'
		]);

		\App\Models\User::create([
			'id' => 2,
			'name' => 'Mário Veterinário',
			'type' => 'VET',
			'email' => 'mariovet@gmail.com',
			'password' => Hash::make('123123123'),
			'crmv' => 'PR-123456'
		]);
    }
}
