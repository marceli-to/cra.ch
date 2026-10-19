<?php

namespace Tests\Feature;

use App\Models\GridItem;
use App\Models\Image;
use App\Models\Project;
use App\Support\Seo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Sitemap, robots.txt, meta and Open Graph tags
 */
class SeoTest extends TestCase
{
    use RefreshDatabase;

    private function project(string $slug, bool $published = true, bool $detailPage = true, ?string $text = null): Project
    {
        $project = Project::create(['title' => ucfirst($slug), 'slug' => $slug, 'order' => 1, 'text' => $text, 'type' => 'Neubau', 'location' => 'Winterthur', 'periode' => '2020']);
        if ($published) {
            $project->flag('isPublish');
        }
        if ($detailPage) {
            $project->flag('hasDetailPage');
        }
        return $project;
    }

    public function testSitemapListsPagesAndPublishedProjectsWithADetailPage()
    {
        $this->project('offen');
        $this->project('entwurf', published: false);
        $this->project('nur-werkliste', detailPage: false);

        $response = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=utf-8');

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $xml->registerXPathNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $locs = array_map('strval', $xml->xpath('//s:loc'));

        $this->assertContains(route('page.worklist'), $locs);
        $this->assertContains(route('page.about.diary'), $locs);
        $this->assertContains(url('/projekt/offen'), $locs);
        $this->assertNotContains(url('/projekt/entwurf'), $locs);
        $this->assertNotContains(url('/projekt/nur-werkliste'), $locs);
    }

    public function testRobotsAllowsIndexingOnlyInProduction()
    {
        $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /')->assertDontSee('Sitemap:');

        $this->app['env'] = 'production';

        $this->get('/robots.txt')->assertOk()
            ->assertHeader('Content-Type', 'text/plain; charset=utf-8')
            ->assertSee('Allow: /')
            ->assertSee('Sitemap: ' . route('sitemap'));
    }

    public function testDescriptionIsPlainTextCutAtAWord()
    {
        $this->assertSame('Die Möttelistrasse in Winterthur', Seo::description('<p>Die M&ouml;ttelistrasse</p><p>in&nbsp;Winterthur</p>'));
        $this->assertSame('eins zwei …', Seo::description('eins zwei drei', 12));
        $this->assertSame('', Seo::description(null));
    }

    public function testProjectPageHasItsTextAsDescriptionAndItsFirstImage()
    {
        $project = $this->project('haus', text: '<p>Ein Haus am &Uuml;fer &amp; im Wald.</p>');
        $image = Image::create(['uuid' => (string) \Str::uuid(), 'name' => 'haus.jpg', 'original_name' => 'haus.jpg', 'extension' => 'jpg', 'size' => 1, 'caption' => 'Ansicht', 'publish' => 1]);
        $grid = $project->grids()->create(['layout' => '1']);
        GridItem::create(['grid_id' => $grid->id, 'position' => 0, 'image_id' => $image->id]);

        $this->get('/projekt/haus')->assertOk()
            ->assertSee('<meta name="description" content="Ein Haus am Üfer &amp; im Wald.">', false)
            ->assertSee('<link rel="canonical" href="' . url('/projekt/haus') . '">', false)
            ->assertSee('/img/haus.jpg?w=1200&amp;h=630&amp;fit=crop&amp;fm=jpg', false)
            ->assertSee('<meta property="og:image:alt" content="Ansicht">', false);
    }

    public function testProjectWithoutTextOrImageFallsBackToItsFactsAndTheDefaultImage()
    {
        $this->project('scheune');

        $this->get('/projekt/scheune')->assertOk()
            ->assertSee('content="Scheune, Neubau, Winterthur, 2020. Cristina Rutz Architekten, Winterthur"', false)
            ->assertSee('<meta property="og:image" content="' . asset('assets/img/og.jpg') . '">', false);
    }

    public function testDefaultOgImageExistsAt1200x630()
    {
        [$width, $height] = getimagesize(public_path(config('seo.image')));

        $this->assertSame([1200, 630], [$width, $height]);
    }

    public function testLoginIsNoindexWithoutCanonical()
    {
        $this->get('/login')->assertOk()
            ->assertSee('<meta name="robots" content="noindex">', false)
            ->assertDontSee('rel="canonical"', false);
    }

    public function testHomeHasJsonLd()
    {
        $html = $this->get('/')->assertOk()->getContent();

        preg_match('#<script type="application/ld\+json">(.*?)</script>#s', $html, $match);
        $data = json_decode($match[1] ?? '', true);

        $this->assertSame('ProfessionalService', $data['@type'] ?? null);
        $this->assertSame('Winterthur', $data['address']['addressLocality'] ?? null);
    }
}
