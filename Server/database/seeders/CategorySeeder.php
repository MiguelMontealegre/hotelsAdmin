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
            'name'           => 'Resorts',
            'description'    => 'Resorts category',
            'fileType'       => 'image/jpg',
            'type'           => 'image',
            'extension'      => 'jpg',
			'documentId'      => '',
            'width'          => 780,
            'height'         => 600,
            'bucketName'     => '',
            'resource'       => '',
            'url'            => 'https://forever.travel-assets.com/flex/flexmanager/images/2023/11/27/e6f49469-b67f-485b-8aaf-f14a79d0d164.jpeg?impolicy=fcrop&h=590&w=448&q=mediumHigh',
            'uploadByUserId' => User::query()->inRandomOrder()->first()->id,
            'mediableId'     => '',
            'mediableType'   => '',
        ]);
		$cats = Media::query()->create([
            'name'           => 'Cabañas',
            'description'    => 'Cabañas category',
            'fileType'       => 'image/jpg',
            'type'           => 'image',
            'extension'      => 'jpg',
			'documentId'      => '',
            'width'          => 780,
            'height'         => 600,
            'bucketName'     => '',
            'resource'       => '',
            'url'            => 'https://forever.travel-assets.com/flex/flexmanager/images/2023/12/02/3623a5ce-2e06-4f01-9072-4055c5d29326.jpeg?impolicy=fcrop&h=590&w=448&q=mediumHigh',
            'uploadByUserId' => User::query()->inRandomOrder()->first()->id,
            'mediableId'     => '',
            'mediableType'   => '',
        ]);
		$birds = Media::query()->create([
            'name'           => 'Vista al mar',
            'description'    => 'Vista al mar category',
            'fileType'       => 'image/jpg',
            'type'           => 'image',
            'extension'      => 'jpg',
			'documentId'      => '',
            'width'          => 780,
            'height'         => 600,
            'bucketName'     => '',
            'resource'       => '',
            'url'            => 'https://forever.travel-assets.com/flex/flexmanager/images/2023/11/24/4fd89b66-18da-4c97-9331-074ec8299784.jpeg?impolicy=resizecrop&rw=896&rh=590&cw=448&cx=-20',
            'uploadByUserId' => User::query()->inRandomOrder()->first()->id,
            'mediableId'     => '',
            'mediableType'   => '',
        ]);
		$rodents = Media::query()->create([
            'name'           => 'Familia',
            'description'    => 'Familia category',
            'fileType'       => 'image/jpg',
            'type'           => 'image',
            'extension'      => 'jpg',
			'documentId'      => '',
            'width'          => 780,
            'height'         => 600,
            'bucketName'     => '',
            'resource'       => '',
            'url'            => 'https://forever.travel-assets.com/flex/flexmanager/images/2023/11/24/VRBO_APFT2_BARCELONA_THERIN_HOUSE_1_2974.jpg?impolicy=fcrop&h=590&w=448&q=mediumHigh',
            'uploadByUserId' => User::query()->inRandomOrder()->first()->id,
            'mediableId'     => '',
            'mediableType'   => '',
        ]);


		Category::query()->create([
			'title' => 'Resorts',
			'description' => 'Categoria Resorts',
			'logoMediaId' => $dogs->id
		]);
		Category::query()->create([
			'title' => 'Cabañas',
			'description' => 'Categorias Cabañas',
			'logoMediaId' => $cats->id
		]);
		Category::query()->create([
			'title' => 'Vista al mar',
			'description' => 'Categoria Vista al mar',
			'logoMediaId' => $birds->id
		]);
		Category::query()->create([
			'title' => 'Familia',
			'description' => 'Categoria de Familia',
			'logoMediaId' => $rodents->id
		]);


    }//end run()


}//end class
