<?php

namespace App;

use WP_Block_Type_Registry;

class CoreModifier
{
    protected string $block_name = '';
    protected array $styles = [];

    /**
     * CoreModifier constructor.
     */
    public function __construct()
    {
        if (!empty($this->block_name) && !empty($this->styles)) {
            $this->register();
        }
    }

    /**
     * Register the block extension.
     */
    public function register(): void
    {
        add_action('init', function () {
            $block_type = WP_Block_Type_Registry::get_instance()->get_registered($this->block_name);

            if ($block_type) {
                $block_type->styles = array_merge(
                    $block_type->styles ?? [],
                    $this->styles
                );
            }
        });
    }
}
