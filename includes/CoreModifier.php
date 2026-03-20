<?php

namespace App;

use WP_Block_Type_Registry;

class CoreModifier
{
    protected static bool $dependency_guard_registered = false;
    protected string $block_name = '';
    protected array $styles = [];

    /**
     * CoreModifier constructor.
     */
    public function __construct()
    {
        if (!self::$dependency_guard_registered) {
            $this->register_dependency_guard();
            self::$dependency_guard_registered = true;
        }

        if (!empty($this->block_name) && !empty($this->styles)) {
            $this->register();
        }
    }

    /**
     * Prevent missing core style dependency handles when block style variations are enqueued.
     */
    protected function register_dependency_guard(): void
    {
        add_action('wp_default_styles', function ($styles) {
            if (!is_object($styles) || !method_exists($styles, 'query') || !method_exists($styles, 'add')) {
                return;
            }

            if (!$styles->query('global-styles', 'registered')) {
                $styles->add('global-styles', false, []);
            }
        }, 1);
    }

    /**
     * Register the block extension.
     */
    public function register(): void
    {
        add_action('init', function () {
            $block_type = WP_Block_Type_Registry::get_instance()->get_registered($this->block_name);

            if (!$block_type) {
                return;
            }

            if (function_exists('register_block_style')) {
                foreach ($this->styles as $style) {
                    register_block_style($this->block_name, $this->normalize_style_args($style));
                }

                return;
            }

            $block_type->styles = array_merge(
                $block_type->styles ?? [],
                $this->styles
            );
        });
    }

    /**
     * Normalize style keys for register_block_style compatibility.
     */
    protected function normalize_style_args(array $style): array
    {
        if (array_key_exists('isDefault', $style) && !array_key_exists('is_default', $style)) {
            $style['is_default'] = (bool) $style['isDefault'];
            unset($style['isDefault']);
        }

        return $style;
    }
}
