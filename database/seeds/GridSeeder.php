<?php
namespace Database\Seeders;
use App\Models\Grid;
use App\Models\Project;
use Illuminate\Database\Seeder;

class GridSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  { 
    $layouts = [
      '1',
      '1:1',
      '1:2',
      '2:1'
    ];

    for($i = 0; $i<=10; $i++)
    {
      $project = Project::inRandomOrder()->first();
      $rand    = mt_rand(0,3);

      Grid::create([
        'layout' => $layouts[$rand],
        'gridable_type' => Project::class,
        'gridable_id' => $project->id,
      ]);
    }
  }
}