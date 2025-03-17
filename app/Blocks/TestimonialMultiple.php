<?php

namespace App\Blocks;

use App\CustomBlock;
use App\Fields\Padding;
use Log1x\AcfComposer\Builder;

class TestimonialMultiple extends CustomBlock
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Testimonial (Multiple)';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A slider block with multiple tesimonials.';

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
    public $icon = 'format-quote';

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
        'testimonials' => [
            [
                'quote' => '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p><p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',
                'author' => 'Quote Author',
                'image' => 'https://via.placeholder.com/350x350',
            ],
            [
                'quote' => '<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>',
                'author' => 'Another Author',
                'image' => 'https://via.placeholder.com/350x350',
            ],
            [
                'quote' => '<p>More text goes here to demonstrate the testimonials third slide in the slider.</p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>',
                'author' => 'Yet Another Author',
                'image' => 'https://via.placeholder.com/350x350',
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
        return [
            'testimonials' => get_field('testimonials') ?: $this->example['testimonials'],
            'background_image' => get_field('background_image') ?: 'https://via.placeholder.com/2500x1000',
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
        $testimonialMultiple = Builder::make('testimonial_multiple');

        $testimonialMultiple
            ->addImage('background_image', [
                'return_format' => 'url',
            ])
            ->addRepeater('testimonials')
            ->addText('quote')
            ->addWysiwyg('author')
            ->addImage('image', [
                'return_format' => 'url',
            ])
            ->endRepeater();

        return $testimonialMultiple->build();
    }

    /**
     * Assets to be enqueued when rendering the block.
     *
     * @return void
     */
    public function assets($block)
    {
        // enqueue remote jquery from cdn
        wp_enqueue_script('splide', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js');

        // enqueue splide css
        wp_enqueue_style('splide', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css');

        parent::assets($block);
    }
}
