<?php
namespace Database\Seeders;
use App\Models\Project;
use App\Models\CategoryProject;
use App\Models\Image;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $faker = \Faker\Factory::create();

    $titles = [
      'Charité Campus Benjamin Franklin',
      'Neubau Bürgerspital Solothurn',
      'Projekt + Konstruktion 2013–2021',
      'Geriatrische Klinik St. Gallen',
      'Spital Zollikerberg',
      'Institut f. Pathologie/Institut f. Rechtsmedizin, St. Gallen',
      'Renovation Kantonsspital Winterthur',
      'Neubau Circle Zürich Flughafen',
      'Franklin Tower Zürich Oerlikon',
    ];

    $periodes = [
      '2022',
      '2021 – 2022',
      '2019 – 2021',
      '2020',
      '2019',
      '2018 – 2021',
      '2017 – 2020',
      '2016',
      '2015 - Heute',
      '2002',
    ];


    for($i = 0; $i<=8; $i++)
    {
      $project = Project::create([
        'title' => [
          'de' => $titles[$i],
          'en' => $titles[$i]
        ],
        'subtitle' => [
          'de' => $faker->sentence(6, true),
          'en' => $faker->sentence(7, true)
        ],
        'abstract' => [
          'de' => $faker->realText(400, 2),
          'en' => $faker->realText(400, 3)
        ],
        'text' => [
          'de' => $faker->realText(500, 2),
          'en' => $faker->realText(450, 3)
        ],
        'text_worklist' => [
          'de' => $titles[$i] . "\n" . $periodes[$i],
          'en' => $titles[$i] . "\n" . $periodes[$i]
        ],
      ]);

      $project->flag('isPublish');
      $project->flag('isWorklist');

      $rand = mt_rand(1,3);
      CategoryProject::create([
        'project_id' => $project->id,
        'category_id' => $rand
      ]);

      for($y = 1; $y < 16; $y++)
      {
        $rand = mt_rand(1,15);
        Image::create([
          'uuid' => \Str::uuid(),
          'name' => 'gmuer-'.$y.'.jpg',
          'original_name' => 'gmuer-'.$y.'.jpg',
          'extension' => 'jpg',
          'size' => '145623'.$y,
          'order' => $rand,
          'publish' => 1,
          'imageable_type' => Project::class,
          'imageable_id' => $project->id,
        ]);
      }
    }
  }
}