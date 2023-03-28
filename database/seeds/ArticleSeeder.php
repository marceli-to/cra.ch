<?php
namespace Database\Seeders;
use App\Models\Article;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Article::create([
      'text' => '<p>Mit Lust am Lernen und Freude bei der Arbeit. Humorvoll und direkt. Strukturiert und unternehmerisch. Stets neugierig, offen für Neues. Respektvoll im Umgang mit Umfeld und Umwelt. Sehr interessiert an Menschen, Räumen und ihren Geschichten. Schweizerin und Spanierin.</p><p>Seit rund neun Jahren entwickle ich mich selbständig weiter – anfänglich in Zürich und seit fünf Jahren in Winterthur.</p>',
      'link' => '/ueber-uns',
    ]);

    Article::create([
      'date' => 'Juni 2022',
      'title' => 'Forum zum Städtebau',
      'text' => '<p>Das Forum möchten von den grossen Parteien in Winterthur wissen, wie sie zu drängenden städte- baulichen Themen stehen und welche Visionen sie für Winterthur haben.</p>',
      'link' => 'https://forum-architektur.ch/ausstellungen',
    ]);
  }
}