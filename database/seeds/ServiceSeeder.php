<?php
namespace Database\Seeders;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Service::create([
      'column_one' => '<h1>Projekt- und Bauleitung</h1><p>Mit meiner langj&auml;hrigen Erfahrung als Leiterin komplexer Projekte umfassen meine heutigen Aufgaben die Planung und Realisierung von Neu- und Umbauten, Machbarkeitsstudien unter Ber&uuml;cksichtigung baurechtlicher und ortsbezogener Rahmenbedingungen.</p><p>Planung und Realisierung von Neu- und Umbauten, Machbarkeitsstudien unter Ber&uuml;cksichtigung baurechtlicher und ortsbezogener Rahmenbedingungen. Mit meiner langj&auml;hrigen Erfahrung als Leiterin komplexer Projekte umfassen meine heutigen Aufgaben die Planung und Realisierung von Neu- und Umbauten, Machbarkeitsstudien unter Ber&uuml;cksichtigung baurechtlicher und ortsbezogener Rahmenbedingungen.</p>',
      'column_two' => '<h2>Leistungen</h2><ul> <li>Machbarkeitsstudien</li><li>Instandhaltungen</li><li>Grobkostenschätzung</li><li>Kostenschätzung</li><li>Kostenvoranschlag</li><li>Baubeschrieb</li><li>Kostenoptimierung</li><li>Variantenvergleich</li></ul><h2>Referenzen</h2><ul> <li>Wohnhaus Möttelistrasse 46</li><li>Villa Domingo Bazan</li><li>Anlage Emil Klöti Strasse</li></ul>',
    ]);
  }
}