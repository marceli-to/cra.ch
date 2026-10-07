<?php
namespace App\Providers;
use App\Actions\Site\GetNavigation;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  public function boot(): void
  {
    setLocale(LC_ALL, 'de');
    \Carbon\Carbon::setLocale('de');

    View::composer('layout.partials.menu', fn ($view) => $view->with((new GetNavigation)->execute()));
  }
}
