<?php
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
    $this->call([
      \Database\Seeders\UserSeeder::class,
      \Database\Seeders\CategorySeeder::class,
      \Database\Seeders\StateSeeder::class,
      \Database\Seeders\ProjectSeeder::class,
      \Database\Seeders\ContactSeeder::class,
      \Database\Seeders\ServiceSeeder::class,
      //\Database\Seeders\AboutSeeder::class,
      \Database\Seeders\ResumeSeeder::class,
      \Database\Seeders\DiarySeeder::class,
      \Database\Seeders\ArticleSeeder::class,
      \Database\Seeders\HomeSeeder::class,
      // \Database\Seeders\GridSeeder::class,
    ]);
  }
}
