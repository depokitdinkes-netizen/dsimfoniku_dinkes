<?php

namespace App\Utils;

class Form
{
    public static function h($int, $label, $id = "")
    {
        return [
            'type' => 'h' . $int,
            'label' => $label,
            'id' => $id
        ];
    }

    public static function input($type, $label, $name)
    {
        return [
            'type' => $type,
            'label' => $label,
            'name' => $name,
        ];
    }

    public static function select($label, $name, $tidak_sesuai = 1)
    {
        return [
            'type' => 'select',
            'label' => $label,
            'name' => $name,
            'option' => [
                [
                    'label' => 'Sesuai',
                    'value' => 0,
                ],
                [
                    'label' => 'Tidak sesuai',
                    'value' => $tidak_sesuai,
                ],
            ],
        ];
    }

    public static function selectc($name, $bobot, $sesuai, $label)
    {
        return [
            'type' => 'selectc',
            'label' => $label,
            'name' => $name,
            'bobot' => $bobot,
            'sesuai' => $sesuai,
            'option' => [
                [
                    'label' => 'Sesuai',
                    'value' => $bobot * $sesuai,
                ],
                [
                    'label' => 'Tidak sesuai',
                    'value' => 0,
                ],
            ],
        ];
    }

    public static function option($value, $label, $id = null)
    {
        return [
            'label' => $label,
            'value' => $value,
            'id' => $id
        ];
    }

    public static function selects($name, $bobot, $label, $options)
    {
        // Auto-generate IDs for options if not provided
        foreach ($options as $index => &$option) {
            if (!isset($option['id']) || $option['id'] === null) {
                $option['id'] = $name . '_option_' . $index;
            }
        }
        
        return [
            'type' => 'selects',
            'label' => $label,
            'name' => $name,
            'bobot' => $bobot,
            'options' => $options,
        ];
    }

    public static function checkbox($label, $name, $score = 1)
    {
        return [
            'type' => 'checkbox',
            'label' => $label,
            'name' => $name,
            'score' => $score
        ];
    }
}
