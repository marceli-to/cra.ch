<?php
namespace Database\Seeders;
use App\Models\Resume;
use Illuminate\Database\Seeder;

class ResumeSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $data = [
      [
        "periode" => "seit 2015",
        "description" => "Cristina Rutz Architekten GmbH, Winterthur eigenes Architekturbüro"
      ],
      [
        "periode" => "seit 2015",
        "description" => "Forum Architektur Winterthur Vorstandsmitglied und Geschäftsleitung"
      ],
      [
        "periode" => "2012 – 2015",
        "description" => "Kiss Rutz GmbH, Zürich lnhaberin und Geschäftsleitung mit Virág Kiss"
      ],
      [
        "periode" => "2004 – 2012",
        "description" => "Stadt Zürich, Amt für Hochbauten Projektentwicklung, Projektleitung"
      ],
      [
        "periode" => "2000 – 2003",
        "description" => "Vera Gloor AG, Zürich Projekt- und Bauleitung"
      ],
      [
        "periode" => "1999",
        "description" => "Escuela Técnica Superior de Arquitectura de Madrid (ETSAM) rchitekturdiplom, Spezialgebiet Statik und Konstruktion"
      ],
      [
        "periode" => "1987",
        "description" => "Schweizerschule in Madrid, Primar-, Sekundarschule und Gymnasium Typ C"
      ],
      [
        "periode" => "1971",
        "description" => "in Winterthur geboren"
      ]
    ];

    foreach ($data as $key => $item)
    {
      $resume = Resume::create([
        'periode' => $item['periode'],
        'description' => $item['description'],
        'order' => $key
      ]);
      $resume->flag('isPublish');
    }

  }
}