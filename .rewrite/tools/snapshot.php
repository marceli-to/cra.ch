<?php
/**
 * Status and body of every public page and every read-only admin API GET,
 * one file per URL, to compare a refactor against the code before it.
 *
 *   APP_DEBUG=false CACHE_STORE=array DB_DATABASE=cristinarutz_prod \
 *     php .rewrite/tools/snapshot.php /tmp/snap-before
 *   diff -r -x queries.tsv /tmp/snap-before /tmp/snap-after
 *
 * Each request runs in a freshly booted app. API requests act as the first
 * admin; nothing is written (toggles and copy are GETs that write, so
 * they're left out).
 */

use App\Models\User;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

$root = dirname(__DIR__, 2);
require $root . '/vendor/autoload.php';

$out = $argv[1] ?? exit("usage: php snapshot.php <outdir>\n");
@mkdir($out, 0777, true);

function boot()
{
  global $root;
  $app = require $root . '/bootstrap/app.php';
  $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
  return $app;
}

function fetch(string $uri, bool $asAdmin): array
{
  $app = boot();
  $queries = 0;
  DB::listen(function () use (&$queries) { $queries++; });
  if ($asAdmin)
  {
    $user = User::where('role', 'admin')->orderBy('id')->first();
    Auth::guard('web')->setUser($user);
    Auth::guard('sanctum')->setUser($user);
  }
  $kernel = $app->make(Kernel::class);
  $request = Request::create($uri, 'GET', [], [], [], ['HTTP_ACCEPT' => str_starts_with($uri, '/api/') ? 'application/json' : 'text/html']);
  $response = $kernel->handle($request);
  $location = $response->headers->get('Location');
  $body = $response->getContent();
  $kernel->terminate($request, $response);
  $app->flush();
  return [$response->getStatusCode(), $location, $body, $queries];
}

// Ids and slugs from the database
$app = boot();
$ids = fn (string $table) => DB::table($table)->orderBy('id')->pluck('id')->all();
$slugs = DB::table('projects')->whereNull('deleted_at')->orderBy('id')->pluck('slug')->all();
$tables = [
  'image' => 'images', 'article' => 'articles', 'service' => 'services', 'about' => 'about',
  'team-member' => 'team_members', 'resume' => 'resumes', 'diary' => 'diaries',
  'contact' => 'contacts', 'category' => 'categories', 'project' => 'projects',
];
$idsByRoute = [];
foreach ($tables as $route => $table)
{
  $idsByRoute[$route] = $ids($table);
}
$teamMembers = $ids('team_members');
$imageNames = DB::table('images')->whereNull('deleted_at')->orderBy('id')->limit(5)->pluck('name')->all();
$app->flush();

$public = ['/', '/leistungen', '/kontakt', '/werkliste', '/ueber-uns/team', '/ueber-uns/tagebuch', '/login', '/password/reset', '/gibt-es-nicht'];
foreach ($slugs as $slug)
{
  $public[] = '/projekt/' . $slug;
}
foreach ($imageNames as $name)
{
  $public[] = '/img/crop/' . $name . '/1200';
}

$api = [
  '/api/user', '/api/images', '/api/articles', '/api/articles/1', '/api/services', '/api/service/images',
  '/api/about', '/api/about/images', '/api/team-members', '/api/resumes', '/api/diaries', '/api/contacts',
  '/api/contact/images', '/api/categories', '/api/states', '/api/projects', '/api/projects/1', '/api/home',
];
foreach ($idsByRoute as $route => $list)
{
  foreach ($list as $id)
  {
    $api[] = "/api/{$route}/{$id}";
  }
}
foreach ($teamMembers as $id)
{
  $api[] = "/api/resumes/{$id}";
}

$requests = [];
$counts = [];
foreach ($public as $uri)
{
  $requests[] = [$uri, false];
}
// Unpublished projects are visible to admins
foreach ($slugs as $slug)
{
  $requests[] = ['/projekt/' . $slug, true];
}
foreach ($api as $uri)
{
  $requests[] = [$uri, true];
}

foreach ($requests as [$uri, $asAdmin])
{
  [$status, $location, $body, $queries] = fetch($uri, $asAdmin);
  // The CSRF token differs per run
  $body = preg_replace('/(name="_token" value=|name="csrf-token" content=)"[^"]*"/', '$1"…"', $body);
  $file = ($asAdmin ? 'admin' : 'guest') . str_replace('/', '_', $uri) . '.txt';
  file_put_contents("{$out}/{$file}", "{$status} {$location}\n{$body}");
  $counts[] = "{$queries}\t{$file}";
}

// Not part of the comparison: diff -r -x queries.tsv
file_put_contents("{$out}/queries.tsv", implode("\n", $counts) . "\n");

echo count($requests) . " requests\n";
