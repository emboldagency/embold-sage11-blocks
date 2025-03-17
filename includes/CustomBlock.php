<?php

namespace App;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Log1x\AcfComposer\Block;

abstract class CustomBlock extends Block
{
    protected $defaultIconBackground = '#fff';

    protected $defaultIconForeground = '#009dff';

    public function __construct($app)
    {
        parent::__construct($app);

        if (is_string($this->icon)) {
            $this->icon = [
                'src' => $this->icon,
                'background' => $this->defaultIconBackground,
                'foreground' => $this->defaultIconForeground,
            ];
        }

        $this->icon['background'] = $this->defaultIconBackground;
        $this->icon['foreground'] = $this->defaultIconForeground;
    }

    public function assets(array $block)
    {
        $plugin_js_file = str_replace('/includes', '', plugin_dir_path(__FILE__) . "resources/scripts/blocks/{$this->slug}.js");

        // Check if the theme has overridden the JavaScript file, or has one in general
        $theme_js_file = get_template_directory() . "/resources/scripts/blocks/{$this->slug}.js";

        if (file_exists($plugin_js_file) || file_exists($theme_js_file)) {
            // Check for the compiled js file
            $theme_js_public_path = get_template_directory() . '/public/js/';

            $compiled_js_files = glob($theme_js_public_path . "{$this->slug}.*js");

            if ($compiled_js_files && $theme_js_file) {
                //# Add a check into the manifest to see if the key exists
                $manifest = json_decode(file_get_contents(get_template_directory() . '/public/manifest.json'), true);
                $manifest_key = Arr::get($manifest, $this->slug . '.js');

                $enqueue_js_file = get_template_directory_uri() . '/public/' . $manifest_key;
            } else {
                $enqueue_js_file = plugins_url("embold-tailwind-blocks/resources/scripts/blocks/{$this->slug}.js");
            }

            wp_enqueue_script("embold-tailwind-blocks-{$this->slug}-js", $enqueue_js_file, [], '1.0', true);
        }
    }

    public function render($block, $content = '', $preview = false, $post_id = 0, $wp_block = false, $context = false)
    {
        $this->block = (object) $block;
        $this->content = $content;
        $this->preview = $preview;
        $this->post_id = $post_id;
        $this->instance = $wp_block;
        $this->context = $context;

        $this->post = get_post($post_id);

        $this->template = is_array($this->template)
            ? $this->handleTemplate($this->template)->toJson()
            : $this->template;

        $this->classes = collect([
            'slug' => Str::start(
                Str::slug($this->slug),
                'wp-block-'
            ),
            'align' => !empty($this->block->align) ?
                Str::start($this->block->align, 'align') :
                false,
            'align_text' => !empty($this->supports['align_text']) ?
                Str::start($this->block->align_text, 'align-text-') :
                false,
            'align_content' => !empty($this->supports['align_content']) ?
                Str::start($this->block->align_content, 'is-position-') :
                false,
            'full_height' => !empty($this->supports['full_height'])
                && !empty($this->block->full_height) ?
                'full-height' :
                false,
            'classes' => $this->block->className ?? false,
        ])->filter()->implode(' ');

        $this->style = $this->getStyle();

        $this->inlineStyle = $this->getInlineStyle();

        $theme_view_path = "blocks/{$this->slug}";

        // Check if the theme already has a view for this block, and return it if so
        if (view()->exists($theme_view_path)) {
            return $this->view($theme_view_path, ['block' => $this]);
        }

        $this->assets($block);

        // If the theme doesn't have a view for this block, return ours
        return $this->view(str_replace('includes/', '', plugin_dir_path(__FILE__) . $this->app->resourcePath("views/blocks/{$this->slug}.blade.php")), ['block' => $this]);
    }
}
