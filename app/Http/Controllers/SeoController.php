<?php
namespace App\Http\Controllers;
use App\Models\Project;
use Illuminate\Http\Response;

/**
 * /sitemap.xml and /robots.txt
 */
class SeoController extends Controller
{
  /**
   * The public pages, and every published project with its own page
   */
  public function sitemap(): Response
  {
    $urls = collect(['page.home', 'page.service', 'page.contact', 'page.worklist', 'page.about.team', 'page.about.diary'])
      ->map(fn ($name) => ['loc' => route($name), 'lastmod' => null]);

    $projects = Project::flagged('isPublish')->flagged('hasDetailPage')->orderBy('order')->get()
      ->map(fn (Project $project) => [
        'loc' => route('page.project.show', $project),
        'lastmod' => $project->updated_at?->toAtomString(),
      ]);

    // Blade can't hold the XML declaration (its "<?" opens PHP)
    $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . view('seo.sitemap', ['urls' => $urls->concat($projects)])->render();

    return response($xml)
      ->header('Content-Type', 'application/xml; charset=utf-8');
  }

  /**
   * Only production may be indexed; a test or staging copy says no
   */
  public function robots(): Response
  {
    $lines = app()->isProduction()
      ? ['User-agent: *', 'Allow: /', '', 'Sitemap: ' . route('sitemap')]
      : ['User-agent: *', 'Disallow: /'];

    return response(implode("\n", $lines) . "\n")
      ->header('Content-Type', 'text/plain; charset=utf-8');
  }
}
