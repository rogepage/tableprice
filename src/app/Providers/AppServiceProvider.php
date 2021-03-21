<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  public function boot()
  {
    //
  }
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->bind('App\Repositories\Contracts\MemberRepositoryInterface', 'App\Repositories\MemberRepository');
  }
}
