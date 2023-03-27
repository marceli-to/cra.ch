<?php
namespace Database\Seeders;
use App\Models\About;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    About::create([
      'description' => '<p>Mit Lust am Lernen und Freude bei der Arbeit. Humorvoll und direkt. Strukturiert und unternehmerisch. Stets neugierig, offen für Neues. Respektvoll im Umgang mit Umfeld und Umwelt. Sehr interessiert an Menschen, Räumen und ihren Geschichten. Schweizerin und Spanierin.<p>Nach meiner Grundausbildung an der Schweizerschule in Madrid, studierte ich an der Escuela Técnica Superior de Arquitectura de Madrid (ETSAM) der Polytechnischen Universität. Während des Studiums sammelte ich in diversen Architekturbüros in Madrid und Zürich wertvolle Erfahrungen. Im Anschluss daran arbeitete ich in leitender Funktion bei verschiedenen Projekten in Zürich. In der Folge wurde ich im Amt für Hochbauten der Stadt Zürich zur Spezialistin für Instandsetzungen und Machbarkeitsstudien, insbesondere bei inventarisierten und denkmalgeschützten Objekten.</p><p>Seit rund zehn Jahren entwickle ich mich selbständig weiter – anfänglich in Zürich und seit sechs Jahren in Winterthur. Mit meiner langjährigen Erfahrung als Leiterin komplexer Projekte umfassen meine heutigen Aufgaben die Planung und Realisierung von Neu- und Umbauten, Machbarkeitsstudien unter Berücksichtigung baurechtlicher und ortsbezogener Rahmenbedingungen sowie die Vorstandsarbeit und die Geschäftsleitung des Forums Architektur Winterthur.</p>',
      'cooperation' => '<h2>Projektbezogene Mitarbeit</h2><p>Stéphanie Müller, Nadine Janesch, Marco Ganzoni, Daniel Gautschi, Paul Ebnöter, Madleina Fischer und Hannes Lukesch</p>',
      'membership' => '<h2>Mitgliedschaften</h2><p>Forum Architektur Winterthur<br>Arealverein Lagerplatz<br>SIA Schweizer Ingenieur- und Architektenverein SIA Sektion Winterthur</p>',
    ]);
  }
}