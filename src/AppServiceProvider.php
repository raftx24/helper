<?php

namespace Raftx24\Helper;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/Config/helper.php' => config_path('raftx24/helper.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__.'/Config/helper.php', 'raftx24.helper'
        );
    }
}
