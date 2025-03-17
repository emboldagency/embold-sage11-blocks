<?php

namespace App\Fields;

use Log1x\AcfComposer\Builder;
use Log1x\AcfComposer\Field;

class Padding extends Field
{
    public static function value()
    {
        $padding_for_all_sides = get_field('padding_for_all_sides');

        if ($padding_for_all_sides) {
            $padding = get_field('padding_all');
        } else {
            $padding_top = get_field('padding_top');
            $padding_right = get_field('padding_right');
            $padding_bottom = get_field('padding_bottom');
            $padding_left = get_field('padding_left');

            $padding = "{$padding_top} {$padding_right} {$padding_bottom} {$padding_left}";
        }

        return $padding;
    }

    /**
     * The field group.
     *
     *
     * @return array
     */
    public function fields()
    {
        $padding = Builder::make('padding_fields');

        $padding
            ->setLocation('block', '==', 'all');

        $padding
            ->addAccordion('padding_accordion', [
                'label' => 'Padding Fields',
                'multi_expand' => false,
                'open' => 0,
                'endpoint' => 0,
            ])
            ->addTrueFalse('padding_for_all_sides', [
                'position' => 'side',
                'default_value' => 0,
                'label' => 'All Sides the Same',
            ])
            ->addSelect('padding_top', [
                'label' => 'Padding Top',
                'default_value' => 'pt-0',
                'return_format' => 'value',
                'multiple' => 0,
                'conditional_logic' => [
                    ['field' => 'padding_for_all_sides', 'operator' => '!=', 'value' => '1'],
                ],
                'choices' => [
                    'pt-0' => 'None',
                    'pt-4' => 'Small',
                    'pt-8' => 'Medium',
                    'pt-12' => 'Large',
                    'pt-16' => 'Extra Large',
                ],
            ])
            ->addSelect('padding_right', [
                'label' => 'Padding Right',
                'default_value' => 'pr-0',
                'return_format' => 'value',
                'multiple' => 0,
                'conditional_logic' => [
                    ['field' => 'padding_for_all_sides', 'operator' => '!=', 'value' => '1'],
                ],
                'choices' => [
                    'pr-0' => 'None',
                    'pr-4' => 'Small',
                    'pr-8' => 'Medium',
                    'pr-12' => 'Large',
                    'pr-16' => 'Extra Large',
                ],
            ])
            ->addSelect('padding_bottom', [
                'label' => 'Padding Bottom',
                'default_value' => 'pb-0',
                'return_format' => 'value',
                'multiple' => 0,
                'conditional_logic' => [
                    ['field' => 'padding_for_all_sides', 'operator' => '!=', 'value' => '1'],
                ],
                'choices' => [
                    'pb-0' => 'None',
                    'pb-4' => 'Small',
                    'pb-8' => 'Medium',
                    'pb-12' => 'Large',
                    'pb-16' => 'Extra Large',
                ],
            ])
            ->addSelect('padding_left', [
                'label' => 'Padding Left',
                'default_value' => 'pl-0',
                'return_format' => 'value',
                'multiple' => 0,
                'conditional_logic' => [
                    ['field' => 'padding_for_all_sides', 'operator' => '!=', 'value' => '1'],
                ],
                'choices' => [
                    'pl-0' => 'None',
                    'pl-4' => 'Small',
                    'pl-8' => 'Medium',
                    'pl-12' => 'Large',
                    'pl-16' => 'Extra Large',
                ],
            ])
            ->addSelect('padding_all', [
                'label' => 'Padding',
                'default_value' => 'p-0',
                'ui' => 1,
                'return_format' => 'value',
                'multiple' => 0,
                'conditional_logic' => [
                    ['field' => 'padding_for_all_sides', 'operator' => '==', 'value' => '1'],
                ],
                'choices' => [
                    'p-0' => 'None',
                    'p-4' => 'Small',
                    'p-8' => 'Medium',
                    'p-12' => 'Large',
                    'p-16' => 'Extra Large',
                ],
            ]);

        $padding->setGroupConfig('menu_order', 99);

        return $padding->build();
    }
}
