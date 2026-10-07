<?php

namespace Tests\Feature;

use App\Models\About;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Diary;
use App\Models\GridItem;
use App\Models\Home;
use App\Models\Image;
use App\Models\Project;
use App\Models\Service;
use App\Models\State;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Every public page renders with a little content. The full comparison
 * against production data: .rewrite/tools/snapshot.php
 */
class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    private function project(string $title, int $order, bool $detailPage = true): Project
    {
        $project = Project::create(['title' => $title, 'slug' => \Str::slug($title), 'order' => $order, 'state_id' => State::firstOrCreate(['title' => 'realisiert'])->id, 'periode' => '2020']);
        $project->flag('isPublish');
        if ($detailPage) {
            $project->flag('hasDetailPage');
        }
        return $project;
    }

    private function grid($owner, string $caption, ?Project $links = null): void
    {
        $image = Image::create(['uuid' => (string) \Str::uuid(), 'name' => 'missing.jpg', 'original_name' => 'x.jpg', 'extension' => 'jpg', 'size' => 1, 'caption' => $caption, 'publish' => 1]);
        $grid = $owner->grids()->create(['layout' => '1']);
        GridItem::create(['grid_id' => $grid->id, 'position' => 0, 'image_id' => $image->id, 'project_id' => $links?->id]);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $a = $this->project('Haus A', 1);
        $b = $this->project('Haus B', 2);
        $this->project('Nur Werkliste', 3, false);
        Category::create(['title' => 'Wohnen', 'slug' => 'wohnen'])->projects()->attach([$a->id, $b->id]);

        $home = new Home();
        $home->id = 1;
        $home->save();
        // On the home page, slots link to a project or page
        $this->grid($home, 'Startbild', $b);
        $this->grid($a, 'Bild A');

        $diary = Diary::create(['description' => 'Tagebuch']);
        $diary->flag('isPublish');
        $this->grid($diary, 'Tagebuchbild');

        About::create(['description' => 'Über uns']);
        TeamMember::updateOrCreate(['slug' => 'cristina'], ['title' => 'Cristina Rutz'])->flag('isPublish');
        Contact::create(['address' => 'Adresse', 'imprint' => 'Impressum']);
        Service::create(['column_one' => 'Leistung eins', 'column_two' => 'Leistung zwei']);
    }

    public function testPagesRender()
    {
        $this->get('/')->assertOk()->assertSee('Haus B');
        $this->get('/werkliste')->assertOk()->assertSee('Haus A')->assertSee('Nur Werkliste');
        $this->get('/ueber-uns/team')->assertOk()->assertSee('Cristina Rutz');
        $this->get('/ueber-uns/tagebuch')->assertOk()->assertSee('Tagebuchbild');
        $this->get('/leistungen')->assertOk()->assertSee('Leistung eins');
        $this->get('/kontakt')->assertOk()->assertSee('Impressum');
        $this->get('/gibt-es-nicht')->assertNotFound();
    }

    public function testMenuListsPublishedProjects()
    {
        $this->get('/kontakt')->assertSee('/projekt/haus-a')->assertSee('/projekt/haus-b')->assertSee('/ueber-uns/tagebuch');
    }

    public function testProjectPageBrowsesInOrder()
    {
        // First: previous is the last, next the second
        $this->get('/projekt/haus-a')->assertOk()->assertSee('Bild A')->assertSee('/projekt/haus-b');
        // Last: next is the first
        $this->get('/projekt/haus-b')->assertOk()->assertSee('/projekt/haus-a');
        // Not in the list (no detail page): between the last and the first
        $this->get('/projekt/nur-werkliste')->assertOk()->assertSee('/projekt/haus-b')->assertSee('/projekt/haus-a');
    }
}
