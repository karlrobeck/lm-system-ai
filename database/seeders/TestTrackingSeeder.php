<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Files;
use App\Models\Scores;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestTrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::query()->find(1);
        $file = Files::query()->where('owner_id','=', $user->id)->first();
        $assessment = Assessment::query()->where('rank','=',1)->where('user_id','=',$user->id)->where('file_id','=',$file->id)->first();

        $assessment['is_failed'] = false;

        $assessment->save();

        Scores::factory()->create([
            'user_id' => $user->id,
            'file_id' => $file->id,
            'correct' => 6,
            'total' => 10,
            'test_type' => 'pre',
            'modality' => 'reading',
            'rank' => 1,
            'is_passed' => true,
        ]);
    }
}
