<?php

namespace MdTech\MdLog;

use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;


class MdLogServiceProvider extends ServiceProvider
{
    public function boot(){
        $this->publishes(array(
            __DIR__ . '/../../config/config.php' => config_path('md_log.php'),
        ));
    }

    public function register(){

        $this->app->singleton('mdLog', function($app) {
            return new MdLog();
        });
    }

    public function provides()
    {
        return ['mdLog'];
    }
}