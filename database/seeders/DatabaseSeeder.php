<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\Models\User::factory(10)->create();
        $counter=1;
        foreach($users as $user) {
            $user->user_id = $counter;
            $user->save();
            $counter++;
        }

    }
}
