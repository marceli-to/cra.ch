<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

/**
 * `role:editor`: users with that role, and admins (who may do everything)
 */
class CheckRole
{
  public function handle(Request $request, Closure $next, string $role)
  {
    abort_unless(in_array($request->user()?->role, [$role, 'admin'], true), 403);

    return $next($request);
  }
}
