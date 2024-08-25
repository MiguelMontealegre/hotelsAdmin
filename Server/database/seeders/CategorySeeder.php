<?php
declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use App\Models\Media\Media;
use Illuminate\Database\Seeder;


class CategorySeeder extends Seeder
{


    /**
     * Run Seeder
     *
     * @return void
     */
    public function run(): void
    {
        $dogs = Media::query()->create([
            'name'           => 'dog',
            'description'    => 'dog category',
            'fileType'       => 'image/jpg',
            'type'           => 'image',
            'extension'      => 'jpg',
			'documentId'      => '',
            'width'          => 780,
            'height'         => 600,
            'bucketName'     => '',
            'resource'       => '',
            'url'            => 'https://ecommercewgs.s3.amazonaws.com/2024-03-27/p2ML6QzCyjWhLqMiLPeq3UZ9J3NHKDmkxREY7Z6O.jpg',
            'uploadByUserId' => User::query()->inRandomOrder()->first()->id,
            'mediableId'     => '',
            'mediableType'   => '',
        ]);
		$cats = Media::query()->create([
            'name'           => 'cat',
            'description'    => 'cat category',
            'fileType'       => 'image/jpg',
            'type'           => 'image',
            'extension'      => 'jpg',
			'documentId'      => '',
            'width'          => 780,
            'height'         => 600,
            'bucketName'     => '',
            'resource'       => '',
            'url'            => 'https://ecommercewgs.s3.amazonaws.com/2024-03-27/snBpd8a0iw243HMJ4jp4RLAChQr9VYzwuxyLeAd2.jpg',
            'uploadByUserId' => User::query()->inRandomOrder()->first()->id,
            'mediableId'     => '',
            'mediableType'   => '',
        ]);
		$birds = Media::query()->create([
            'name'           => 'bird',
            'description'    => 'bird category',
            'fileType'       => 'image/jpg',
            'type'           => 'image',
            'extension'      => 'jpg',
			'documentId'      => '',
            'width'          => 780,
            'height'         => 600,
            'bucketName'     => '',
            'resource'       => '',
            'url'            => 'https://ecommercewgs.s3.amazonaws.com/2024-03-27/H92qCMOFdZBj2IHJXSkvhpJewtkWm567uJPmfH0K.jpg',
            'uploadByUserId' => User::query()->inRandomOrder()->first()->id,
            'mediableId'     => '',
            'mediableType'   => '',
        ]);
		$rodents = Media::query()->create([
            'name'           => 'rodent',
            'description'    => 'rodent category',
            'fileType'       => 'image/jpg',
            'type'           => 'image',
            'extension'      => 'jpg',
			'documentId'      => '',
            'width'          => 780,
            'height'         => 600,
            'bucketName'     => '',
            'resource'       => '',
            'url'            => 'https://ecommercewgs.s3.amazonaws.com/2024-03-27/gJV4krnaIx9XwQmro8yJYeLcu5ZQUKcml2NEsssP.jpg',
            'uploadByUserId' => User::query()->inRandomOrder()->first()->id,
            'mediableId'     => '',
            'mediableType'   => '',
        ]);


		Category::query()->create([
			'title' => 'Perros',
			'description' => 'Categoria de Perros',
			'logoMediaId' => $dogs->id
		]);
		Category::query()->create([
			'title' => 'Gatos',
			'description' => 'Categoria de Gatos',
			'logoMediaId' => $cats->id
		]);
		Category::query()->create([
			'title' => 'Aves',
			'description' => 'Categoria de Aves',
			'logoMediaId' => $birds->id
		]);
		Category::query()->create([
			'title' => 'Roedores',
			'description' => 'Categoria de Roedores',
			'logoMediaId' => $rodents->id
		]);


    }//end run()


}//end class
