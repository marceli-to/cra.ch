<?php
namespace Database\Seeders;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
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
        'title' => 'Projekt- und Bauleitung',
        'slug' => 'projekt-und-bauleitung',
      ],
      [
        'title' => 'Wettbewerbe, Studienaufträge',
        'slug' => 'wettbewerbe-studienauftraege',
      ],
      [
        'title' => 'Machbarkeitsstudie, Erneuerungsstrategien, Umnutzungen',
        'slug' => 'machbarkeitsstudie-erneuerungsstrategien-umnutzungen',
      ],
    ];
    
    foreach($data as $d)
    {
      Category::create([
        'title' => $d['title'],
        'slug' => $d['slug'],
      ]);
    }
  }
}