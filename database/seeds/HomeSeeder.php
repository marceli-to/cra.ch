<?php
namespace Database\Seeders;
use App\Models\Home;
use Illuminate\Database\Seeder;

class HomeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Home::create([
      'id' => 1
    ]);
  }
}