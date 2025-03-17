<?php

namespace App\Blocks;

use App\CustomBlock;
use App\Fields\Padding;
use Log1x\AcfComposer\Builder;

class Accordion extends CustomBlock
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Accordion';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Accordion block.';

    /**
     * The block category.
     *
     * @var string
     */
    public $category = 'embold';

    /**
     * The block icon.
     *
     * @var string|array
     */
    public $icon = 'editor-ul';

    /**
     * The block keywords.
     *
     * @var array
     */
    public $keywords = [];

    /**
     * The block post type allow list.
     *
     * @var array
     */
    public $post_types = [];

    /**
     * The parent block type allow list.
     *
     * @var array
     */
    public $parent = [];

    /**
     * The default block mode.
     *
     * @var string
     */
    public $mode = 'preview';

    /**
     * The default block alignment.
     *
     * @var string
     */
    public $align = '';

    /**
     * The default block text alignment.
     *
     * @var string
     */
    public $align_text = '';

    /**
     * The default block content alignment.
     *
     * @var string
     */
    public $align_content = '';

    /**
     * The supported block features.
     *
     * @var array
     */
    public $supports = [
        'align' => true,
        'align_text' => false,
        'align_content' => false,
        'full_height' => false,
        'anchor' => false,
        'mode' => true,
        'multiple' => true,
        'jsx' => true,
    ];

    /**
     * The block styles.
     *
     * @var array
     */
    public $styles = [];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'title' => 'H3 Style Lorem Ipsum Dolor Sit Amet',
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        // Make sure this is only true on the first instance of the accordion block.
        $is_first_accordion_instance = ! get_query_var('is_first_accordion_instance', false);

        // This is no longer needed, but I'm leaving it in as an example
        // of how you can add code (maybe a <style> section) to only the
        // first instance of a block on the page.
        if ($is_first_accordion_instance) {
            set_query_var('is_first_accordion_instance', true);
        }

        return [
            'title' => $this->title(),
            'padding' => Padding::value(),
            'inline_style' => $is_first_accordion_instance,
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $accordion = Builder::make('accordion_title');

        $accordion->addText('accordion_title');

        return $accordion->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function title()
    {
        return get_field('accordion_title') ?: $this->example['title'];
    }

    /**
     * Assets to be enqueued when rendering the block.
     *
     * @return void
     */
    public function assets($block)
    {
        global $is_first_accordion_instance;
        $is_first_accordion_instance = true;

        add_filter('render_block', [$this, 'add_custom_style_to_first_accordion_instance'], 10, 2);
    }

    /**
     * Add custom style to the first accordion instance.
     *
     * @param  string  $block_content  The block content.
     * @param  array  $block  The block data.
     * @return string
     */
    public function add_custom_style_to_first_accordion_instance($block_content, $block)
    {
        global $is_first_accordion_instance;

        if ($is_first_accordion_instance && $block['blockName'] === 'app/accordion') {
            $style = '<style>body { color: red; }</style>';
            $block_content = $style.$block_content;
            $is_first_accordion_instance = false;
        }

        return $block_content;
    }
}
