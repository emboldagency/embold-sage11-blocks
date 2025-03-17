<?php

namespace App;

class InitBlocks
{
    private static $namespace = 'Blocks';

    public static function initialize($composer)
    {
        $namespace = self::$namespace;

        $classes = config('emblocks.block_classes', []);

        foreach ($classes as $classname) {
            $path = "app/{$namespace}/{$classname}.php";

            if (! file_exists(get_theme_file_path($path))) {
                $class = '\App\\' . $namespace . '\\' . $classname;

                (new $class($composer))->compose();
            }
        }
    }
}
