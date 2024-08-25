<?php
declare(strict_types=1);

use Database\Seeders\TagSeeder;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\MediaSeeder;
use Database\Seeders\CategorySeeder;


/**
 * Class DatabaseSeeder
 *
 * @extends  Seeder factory
 * @category Seeders
 * @package  Database\Seeders

 */
class DatabaseSeeder extends Seeder
{


    /**
     * Run Seeder
     *
     * @return void
     */
    public function run(): void
    {
        $this->call(
            [
                UserSeeder::class,
                MediaSeeder::class,
				CategorySeeder::class,
				TagSeeder::class
            ]
        );

    }//end run()


}//end class
