<?php

namespace App\Blocks;

use App\CustomBlock;
use App\Fields\Padding;
use Log1x\AcfComposer\Builder;

class Slider extends CustomBlock
{
    /**
     * The block name.
     *
     * @var string
     */
    public $name = 'Slider';

    /**
     * The block description.
     *
     * @var string
     */
    public $description = 'A simple Slider block.';

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
    public $icon = 'slides';

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

    ];

    /**
     * The block preview example data.
     *
     * @var array
     */
    public $example = [
        'slides' => [
            [
                'title' => '<p>A First</p><p>Slider Goes Here</p>',
                'body' => '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</p>',
                'link' => '/',
                'image' => 'https://placebear.com/804/462',
            ],
            [
                'title' => '<p>Number Two</p><p>Comes After One</p>',
                'body' => '<p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>',
                'link' => '/',
                'image' => 'https://placebear.com/824/482',
            ],
            [
                'title' => '<p>A Third</p><p>Slider Here</p>',
                'body' => '<p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>',
                'link' => '/',
                'image' => 'https://placebear.com/814/472',
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
            'slides' => $this->slides(),
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
        $slider = Builder::make('slider');

        $slider
            ->addRepeater('slides')
            ->addWysiwyg('title')
            ->addWysiwyg('body')
            ->addText('link')
            ->addImage('image', [
                'return_format' => 'url',
            ])
            ->endRepeater();

        return $slider->build();
    }

    /**
     * Return the items field.
     *
     * @return array
     */
    public function slides()
    {
        return get_field('slides') ?: $this->example['slides'];
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
