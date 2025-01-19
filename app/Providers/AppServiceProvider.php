<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Validator::extend('file_size', function ($attribute, $value, $parameters, $validator) {
            $maxSize = $parameters[0] * 1024; // Convert KB to bytes
            return $value->getSize() <= $maxSize;
        });

        //
        $isProd = $this->app->environment() === 'local';

        Model::preventLazyLoading(!$isProd);
        Model::preventSilentlyDiscardingAttributes(!$isProd);
    }
}
