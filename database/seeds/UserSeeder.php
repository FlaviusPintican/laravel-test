<?php declare(strict_types=1);

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            DB::table('user')->insert([
               'username' => Str::random(10),
               'password' => Hash::make("password$i"),
               'first_name' => Str::random(10),
               'family_name' => Str::random(10),
               'email' => Str::random(10).'@gmail.com',
               'phone_number' => '+4' . Str::random(10),
            ]);
        }
    }
}
