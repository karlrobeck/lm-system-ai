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

        $study_notes = "
# Keynotes: *No Man Is an Island* by John Donne

- **Interconnectedness of Humanity**  
    - No person exists in isolation; everyone is part of a greater whole.  
    - Each individual contributes to the collective existence of mankind.  

- **Metaphor of the Continent**  
    - Humanity is compared to a continent, where each person is a piece of the whole.  
    - The loss of one part (a person) affects the entire structure.  

- **Impact of Loss**  
    - The death or suffering of any individual affects all of humanity.  
    - People should recognize their shared responsibility and connection.  

- **Symbolism of the Bell**  
    - The tolling of the funeral bell signifies a universal reminder of mortality.  
    - It emphasizes that death is not just an individual event but a loss to all.  

- **Moral Reflection**  
    - Encourages empathy, unity, and awareness of shared human experiences.  
    - Reinforces the idea that one should not remain indifferent to the struggles of others.  

";

        $owner = User::query()->find(1);
        
        Files::factory()->create([
            'owner_id' => $owner->id,
            'name' => $filename,
            'path' => $filename,
            'study_notes' => $study_notes,
            'is_ready' => true,
        ]);
    }
}
