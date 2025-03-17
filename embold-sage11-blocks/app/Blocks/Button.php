<?php

namespace App\Blocks;

use App\CustomBlock;
use App\Fields\Padding;
use Log1x\AcfComposer\Builder;

class Button extends CustomBlock
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Button';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Button block.';

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
    public $icon = 'button';

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
    public $styles = [
        [
            'name' => 'default',
            'label' => 'Default',
            'isDefault' => true,
        ],
        [
            'name' => 'secondary',
            'label' => 'Secondary',
        ],
        [
            'name' => 'ghost',
            'label' => 'Ghost',
        ],
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'link' => '/',
        'text' => 'Button',
        'full_width' => false,
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'full_width' => get_field('full_width') ?: $this->example['full_width'],
            'link' => get_field('link') ?: $this->example['link'],
            'text' => get_field('text') ?: $this->example['text'],
            'padding' => Padding::value(),
        ];
    }

    /**
     * The block field group.
     *
     * @return array
     */
    public function fields()
    {
        $button = Builder::make('button');

        $button->addTrueFalse('full_width', [
            'default_value' => 0,
            'label' => 'Full Width',
        ])
            ->addText('link')
            ->addText('text');

        return $button->build();
    }

    /**
     * Assets to be enqueued when rendering the block.
     *
     * @return void
     */
    public function assets($block)
    {
        //
    }
}
