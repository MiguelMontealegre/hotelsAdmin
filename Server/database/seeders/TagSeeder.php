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
			'title' => 'Limpieza',
			'description' => 'Limpieza Tag',
		]);
		Tag::query()->create([
			'title' => 'Alimento',
			'description' => 'Alimento Tag',
		]);
		Tag::query()->create([
			'title' => 'Ropa',
			'description' => 'Ropa Tag',
		]);
		Tag::query()->create([
			'title' => 'Juguetes',
			'description' => 'Juguetes Tag',
		]);


    }//end run()


}//end class
