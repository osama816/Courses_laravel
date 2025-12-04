<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LlmService;
use App\Services\MCPService;
use App\Services\MCPLLMOrchestrator;

class MCPServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //  LlmService  Singleton
        $this->app->singleton(LlmService::class, function ($app) {
            return new LlmService();
        });

        //  MCPService  Singleton
        $this->app->singleton(MCPService::class, function ($app) {
            return new MCPService();
        });

        //  MCPLLMOrchestrator
        $this->app->singleton(MCPLLMOrchestrator::class, function ($app) {
            return new MCPLLMOrchestrator(
                $app->make(MCPService::class),
                $app->make(LLMService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
