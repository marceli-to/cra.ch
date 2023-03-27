<?php
namespace Database\Seeders;
use App\Models\Diary;
use Illuminate\Database\Seeder;

class DiarySeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $diary = Diary::create([
      'title' => 'Tagebuch',
      'description' => '<p>Stets neugierig, offen für Neues. Respektvoll im Umgang mit Umfeld und Umwelt. Sehr interessiert an Menschen, Räumen und ihren Geschichten. Während des Studiums sammelte ich in diversen Architekturbüros in Madrid und Zürich wertvolle Erfahrungen.</p>',
    ]);
    $diary->flag('isPublish');
  }
}