<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Model::unguard();
    }

    public function boot(): void
    {
        JsonResource::withoutWrapping();
        Model::shouldBeStrict(! $this->app->isProduction());

    }
}
