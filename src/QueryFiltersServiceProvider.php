<?php

namespace Esign\QueryFilters;

use Esign\QueryFilters\Console\FilterMakeCommand;
use Illuminate\Support\ServiceProvider;

class QueryFiltersServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                FilterMakeCommand::class,
            ]);
        }
    }

    public function register()
    {
    }
}
