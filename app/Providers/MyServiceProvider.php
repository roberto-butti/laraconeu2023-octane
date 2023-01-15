<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class MyServiceProvider extends ServiceProvider
{
    public function __construct($app)
    {
        parent::__construct($app);
        //Log::debug("__METHOD__");
        Log::debug("CONSTRUCT");
    }

    public function register()
    {
        // Log::debug(__METHOD__);
        Log::debug("REGISTER");
    }

    public function boot()
    {
        // Log::debug(__METHOD__);
        Log::debug("BOOT");
    }
}
