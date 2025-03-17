<?php

namespace App\Console;

use Log1x\AcfComposer\Console\MakeCommand;

class CustomBlockMakeCommand extends MakeCommand
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'embold:block {name* : The name of the block}
                            {--construct : Generate block properties inside of `__construct`}
                            {--force : Overwrite any existing files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new ACF custom block type';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Block';

    /**
     * The view stub used when generated.
     *
     * @var string|bool
     */
    protected $view = 'block';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('construct')) {
            return $this->resolveStub('block.construct');
        }

        return $this->resolveStub('block');
    }

    /**
     * Get the resolved stub file path.
     *
     * @param  string  $name
     * @return string
     */
    protected function resolveStub($name)
    {
        $path = '/' . $name . '.stub';
        $stubsPath = resource_path('stubs/emblocks') . $path;

        if ($this->files->exists($stubsPath)) {
            return $stubsPath;
        }

        return __DIR__ . '/stubs' . $path;
    }
}
