<?php

namespace App\Providers;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\SubscriberVerifyEmailNotification;
use App\Models\Page;
use App\Observers\PageObserver;

class EventServiceProvider extends ServiceProvider
{
  /**
   * The event listener mappings for the application.
   *
   * @var array
   */
  protected $listen = [

    Registered::class => [
      SendEmailVerificationNotification::class,
    ],

  ];

  /**
   * Register any events for your application.
   *
   * @return void
   */
  public function boot()
  {
    parent::boot();
    Page::observe(PageObserver::class);
  }
}
