<?php
namespace Database\Seeders;
use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Contact::create([
      'address' => '<p>Cristina Rutz Architekten GmbH Lagerplatz 6, 8400 Winterthur</p><p>+41 52 721 50 50, cr@cristinarutz.ch</p>',
      'description' => '<p>Unser Büro befindet sich auf dem Lagerplatz in der Nähe des Hauptbahnhofs Winterthur, im 1. Obergeschoss des Gebäudes 190. Wir geniessen eine schöne Sicht auf die Halle 53 am Katharina-Sulzer-Platz und das Sulzer-Areal Stadtmitte, Lokstadt.</p>',
      'maps_uri' => 'https://goo.gl/maps/84AH5sWpzNEQqx4U7',
      'imprint' => null,
    ]);
  }
}