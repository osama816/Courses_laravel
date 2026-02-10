<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\LLMService;
use App\Services\MCPLLMOrchestrator;
use Laravel\Mcp\Facades\Mcp;
use App\Mcp\Servers\SupportServer;

class MCPServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // LLMService Singleton
        $this->app->singleton(LLMService::class, function ($app) {
            return new LLMService();
        });

        // MCPLLMOrchestrator
        $this->app->singleton(MCPLLMOrchestrator::class, function ($app) {
            return new MCPLLMOrchestrator(
                $app->make(LLMService::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register MCP Server for local use
        Mcp::local('support', SupportServer::class);
    }
}
