<?php

namespace App;

use Log1x\AcfComposer\AcfComposer;

class InitFields
{
    private static $namespace = 'Fields';

    public static function initialize(AcfComposer $composer)
    {
        $namespace = self::$namespace;

        $classes = config('emblocks.field_classes', []);

        foreach ($classes as $classname) {
            $path = "app/{$namespace}/{$classname}.php";

            if (! file_exists(get_theme_file_path($path))) {
                $class = '\App\\' . $namespace . '\\' . $classname;

                (new $class($composer))->compose();
            }
        }
    }
}
