<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

use App\Models\Unit;

class UnitSeeder extends Seeder {

	public function run()
	{
		Unit::truncate();
        $users = User::all();

        foreach($users as $user) {
            Unit::create([
                'name' => 'REPS',
                'user_id' => $user->id
            ]);

            Unit::create([
                'name' => 'TIME',
                'user_id' => $user->id
            ]);
        }
		

	}

}