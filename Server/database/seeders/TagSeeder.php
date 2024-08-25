<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;


class TagSeeder extends Seeder
{


    /**
     * Run Seeder
     *
     * @return void
     */
    public function run(): void
    {
		Tag::query()->create([
			'title' => 'Mascotas',
			'description' => 'Mascotas Tag',
		]);
		Tag::query()->create([
			'title' => 'Todo incluido',
			'description' => 'Todo incluido Tag',
		]);
		Tag::query()->create([
			'title' => 'Lujo',
			'description' => 'Lujo Tag',
		]);
		Tag::query()->create([
			'title' => 'Barato',
			'description' => 'Barato Tag',
		]);


    }//end run()


}//end class
