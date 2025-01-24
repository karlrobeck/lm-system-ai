<?php

namespace Database\Seeders;

use App\Models\Files;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class FileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create a file with a text content. and save the file to the database
        $content = "
# No Man Is an Island 

No man is an island,

Entire of itself;

Every man is a piece of the continent,

A part of the main.

If a clod be washed away by the sea,

Europe is the less,

As well as if a promontory were:

As well as if a manor of thy friend's

Or of thine own were.

Any man's death diminishes me,

Because I am involved in mankind.

And therefore never send to know for whom the bell tolls;

It tolls for thee.
        ";
        $filename = 'nomanisanisland.txt';
        // Save the file to the storage
        Storage::put($filename, $content);

        $owner = User::query()->find(1);
        
        Files::factory()->create([
            'owner_id' => $owner->id,
            'name' => $filename,
            'path' => $filename,
            'is_ready' => true,
        ]);
    }
}
