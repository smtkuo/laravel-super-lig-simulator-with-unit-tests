<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Team;
use Illuminate\Support\Facades\File;
use Faker\Factory as Faker;

class TeamsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $json = File::get('resources/files/superlig.json');
        $teams = json_decode($json, true);
        $faker = Faker::create();
        foreach ($teams as $teamData) {
            Team::create([
                'name' => $teamData['team_name'],
                'power' => $faker->numberBetween($min = 1, $max = 100),
                'team_stadium' =>  $teamData['team_stadium']
            ]);
        }
    }
}
