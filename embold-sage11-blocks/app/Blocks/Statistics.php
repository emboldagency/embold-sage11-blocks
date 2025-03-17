<?php

namespace App\Blocks;

use App\CustomBlock;
use App\Fields\Padding;
use Log1x\AcfComposer\Builder;

class Statistics extends CustomBlock
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Statistics';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Statistics block.';

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
    public $icon = 'image-filter';

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
        'align' => false,
        'align_text' => false,
        'align_content' => false,
        'full_height' => false,
        'anchor' => false,
        'mode' => true,
        'multiple' => true,
        'jsx' => false,
    ];

    /**
     * The block styles.
     *
     * @var array
     */
    public $styles = [
        [
            'name' => 'list',
            'label' => 'List',
            'isDefault' => true,
        ],
        [
            'name' => 'grid',
            'label' => 'Grid',
        ],
        [
            'name' => 'featured',
            'label' => 'Featured',
        ],
        [
            'name' => 'full-width',
            'label' => 'Full Width',
        ],
    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'statistics' => [
            [
                'number' => '0000',
                'description' => '<p>Theme small</p><p>description here</p>',
            ],
            [
                'number' => '0001',
                'description' => '<p>Stat small</p><p>description here</p>',
            ],
            [
                'number' => '0002',
                'description' => '<p>Stat small</p><p>description here</p>',
            ],
            [
                'number' => '0003',
                'description' => '<p>Stat small</p><p>description here</p>',
            ],
        ],
    ];

    /**
     * Data to be passed to the block before rendering.
     *
     * @return array
     */
    public function with()
    {
        // Append classes to the blocks class list to clear floats
        $this->classes .= ' before:table before:clear-both';

        return [
            'featured_background' => get_field('featured_background'),
            'statistics' => $this->statistics(),
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
        $statistics = Builder::make('statistics');

        $statistics
            ->addImage('featured_background', [
                'return_format' => 'url',
            ])
            ->addRepeater('statistics')
            ->addText('number')
            ->addWysiwyg('description')
            ->endRepeater();

        return $statistics->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function statistics()
    {
        return get_field('statistics') ?: $this->example['statistics'];
    }

    /**
     * Assets to be enqueued when rendering the block.
     *
     * @return void
     */
    public function assets($block)
    {
        // Load the default JS file or our theme JS file automagically
        parent::assets($block);
    }
}
