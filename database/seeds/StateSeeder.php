<?php
namespace Database\Seeders;
use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // states: in Bearbeitung, realisiert, abgeschlossen, 
    State::create([
      'title' => 'in Bearbeitung',
    ]);
    State::create([
      'title' => 'realisiert',
    ]);
    State::create([
      'title' => 'abgeschlossen',
    ]);
  }
}