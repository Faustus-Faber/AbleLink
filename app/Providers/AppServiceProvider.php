<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Services\OcrAndSimplify\Ocr\OcrEngine::class,
            \App\Services\OcrAndSimplify\Ocr\TesseractOcrEngine::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $mainPath = database_path('migrations');
        $directories = glob($mainPath . '/*' , GLOB_ONLYDIR);
        $paths = array_merge([$mainPath], $directories);

        $this->loadMigrationsFrom($paths);
    }
}
