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

    // Create mt_random titles in german for architectorial projects (i.E. Wohnhaus Musterstrasse 1, Villa Kunterbunt, etc.)
    $titles = [
      'Wohnhaus Möttelistrasse 46',
      'Villa Kunterbunt',
      'Wohnhaus Trilo',
      'Villa Domingo Bazan',
      'Wohnhaus Im Zwei',
      'Geschäftshaus Giesshübel',
      'Wohnhaus Bergblumenstrasse',
      'Regenbecken Talacker',
      'Fussgängererschliessung «Parkhaus Technikum Nord»',
      'Tagesbetreuung Hebel',
      'Haus Eber',
      'Anlage Untere Vogelsangstrasse',
      'Garderobe P33',
      'Alterzentrum Rosental',
      'Wohnhaus Im Hof 5',
      'Gartenhaus Hofmattstrasse'
    ];

    // Periode range: 2010 - 2022, add mt_randomly "ab [Year]

    $periodes = [
      '2010 - 2012',
      '2012 - 2014',
      '2014 - 2016',
      '2016 - 2018',
      '2018 - 2020',
      '2020 - 2022',
      '2010 - 2012',
      '2012 - 2014',
      '2014 - 2016',
      'ab 2020',
      'ab 2019',
      'ab 2018',
      'ab 2022',
      'ab 2023'
    ];

    // Services are an unordered list, consiting of 'Projekt- und Bauleitung', 'Ausführung', 'Baumanagement', 'Bauingenieurwesen', 'Bauökologie', 'Bauökonomie'
    // For each entry take 1 to 4 services mt_randomly and make a <ul><li> list out of it
    $services = [
      '<ul>
        <li>Bauökologie</li>
        <li>Projekt- und Bauleitung</li>
        <li>Bauingenieurwesen</li>
      </ul>',
      '<ul>
        <li>Ausführung</li>
        <li>Bauökonomie</li>
      </ul>',
      '<ul>
        <li>Baumanagement</li>
        <li>Bauingenieurwesen</li>
        <li>Projekt- und Bauleitung</li>
        <li>Bauökologie</li>
      </ul>',
      '<ul>
        <li>Projekt- und Bauleitung</li>
        <li>Bauökologie</li>
      </ul>'
    ];

    // Generate types. Types are: Instansetzung, Umbau, Neubau, Sanierung, Erweiterung, Ersatzneubau
    $types = [
      'Instansetzung',
      'Umbau',
      'Neubau',
      'Sanierung',
      'Erweiterung',
      'Ersatzneubau'
    ];
    // Locations are cities in switzerland and europe
    $locations = [
      'Winterthur',
      'Zürich',
      'Bern',
      'Basel',
      'Lausanne',
      'St. Gallen',
      'Lugano',
      'Genf',
      'Biel',
      'Lucerne',
      'Schaffhausen',
      'Madrid',
      'Barcelona',
      'Paris',
      'London',
      'Amsterdam',
    ];

    for($i = 0; $i<=15; $i++)
    {
      $title = $titles[mt_rand(0,15)];

      // check if title already exists
      $project = Project::where('slug', \Str::slug($title))->first();
      if($project) continue;

      $project = Project::create([
        'title' => $title,
        'slug' => \Str::slug($title),
        'text' => $faker->paragraph(5),
        'text_services' => $services[mt_rand(0,3)],
        'text_info' => '<h2>Adresse<h2><p>Möttelistrasse 46, Winterthur</p><h2>Instandsetzung</h2><p>2020 – 2022</p><h2>Mitarbeit</h2><p>Daniel Gautschi, Stéphanie Müller, Nadine Janesch</p><h2>Fachplaner</h2><p>Planforum GmbH, Oberli Bauingenieure AG, BWS Bauphysik AG</p>',
        'type' => $types[mt_rand(0,5)],
        'location' => $locations[mt_rand(0,15)],
        'periode' => $periodes[mt_rand(0,13)],
        'state_id' => mt_rand(1,3),
      ]);

      $project->flag('isPublish');
      // Set flags for every 3rd project
      if($i % 3 == 0) $project->flag('isWorklist');

      CategoryProject::create([
        'project_id' => $project->id,
        'category_id' => mt_rand(1,3)
      ]);

      for($y = 1; $y <= 13; $y++)
      {
        $rand = mt_rand(1,13);
        $random_string = \Str::random(10);
        Image::create([
          'uuid' => \Str::uuid(),
          'name' => 'cra-' . $y .'.jpg',
          'original_name' => 'cra-' . $y .'.jpg',
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