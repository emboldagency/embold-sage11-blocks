<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Console\CustomBlockMakeCommand;

class EmboldServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Log1x\AcfComposer\Block', \App\CustomBlock::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishAssets([
            'embold-config' => [
                ['config/emblocks.php', config_path('emblocks.php')],
            ],
            'embold-blocks' => [
                ['app/Blocks', base_path('app/Blocks')],
                ['resources/views/blocks', resource_path('views/blocks')],
                ['resources/styles/blocks', resource_path('styles/blocks')],
                ['resources/scripts/blocks', resource_path('scripts/blocks')],
            ],
            'embold-fields' => [
                ['app/Fields', base_path('app/Fields')],
            ],
            'embold-modifiers' => [
                ['app/Modifiers', base_path('app/Modifiers')],
            ],
            'embold-stubs' => [
                ['includes/Console/stubs', resource_path('stubs/emblocks')],
            ],
        ]);

        if ($this->app->runningInConsole()) {
            $this->commands([
                CustomBlockMakeCommand::class,
            ]);
        }
    }

    /**
     * Helper function to publish assets.
     *
     * @param array $assets
     * @return void
     */
    protected function publishAssets(array $assets)
    {
        foreach ($assets as $tag => $paths) {
            foreach ($paths as $path) {
                $this->publishes([__DIR__ . '/../../' . $path[0] => $path[1]], $tag);
            }
        }
    }
}
