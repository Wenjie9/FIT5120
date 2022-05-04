<?php

namespace cBuilder\Helpers;

use cBuilder\Classes\CCBProTemplate;
use cBuilder\Classes\CCBTemplate;

/**
 * Cost Calculator Fields Helper
 */
class CCBFieldsHelper {

    private static $fileFieldFormats = [
        'png', 'jpg/jpeg', 'gif', 'webp', 'svg', 'pdf', 'doc/docx', 'ppt/pptx',
        'pps/ppsx', 'odt', 'xls/xlsx', 'psd', 'key', 'mp3', 'm4a', 'ogg', 'wav',
        'mp4', 'mov', 'avi', 'mpg', 'ogv','3gp', '3g2'];
    /** Field templates */
    public static function get_fields_templates() {

        $templates = [
            'line'         => CCBTemplate::load( 'frontend/fields/cost-line' ),
            'html'         => CCBTemplate::load( 'frontend/fields/cost-html' ),
            'toggle'       => CCBTemplate::load( 'frontend/fields/cost-toggle' ),
            'text-area'    => CCBTemplate::load( 'frontend/fields/cost-text' ),
            'checkbox'     => CCBTemplate::load( 'frontend/fields/cost-checkbox' ),
            'quantity'     => CCBTemplate::load( 'frontend/fields/cost-quantity' ),
            'radio-button' => CCBTemplate::load( 'frontend/fields/cost-radio' ),
            'range-button' => CCBTemplate::load( 'frontend/fields/cost-range' ),
            'drop-down'    => CCBTemplate::load( 'frontend/fields/cost-drop-down' ),
        ];

        if ( ccb_pro_active() ) {
            $templates['date-picker']           = CCBProTemplate::load( 'frontend/fields/cost-date-picker' );
            $templates['multi-range']           = CCBProTemplate::load( 'frontend/fields/cost-multi-range' );
            $templates['file-upload']           = CCBProTemplate::load( 'frontend/fields/cost-file-upload' );
            $templates['drop-down-with-image']  = CCBProTemplate::load( 'frontend/fields/cost-drop-down-with-image' );
        }

        return $templates;
    }

    /** Get all posible fields */
    public static function fields() {

        return [
            [
                'name'        => __( 'Checkbox', 'cost-calculator-builder' ),
                'alias'       => 'checkbox',
                'type'        => 'checkbox',
                'icon'        => 'fas fa-check-square',
                'description' => 'checkbox fields'
            ],
            [
                'name'        => __( 'Radio', 'cost-calculator-builder' ),
                'alias'       => 'radio',
                'type'        => 'radio-button',
                'icon'        => 'fas fa-dot-circle',
                'description' => 'radio fields'
            ],
            [
                'name'        => __( 'Date Picker', 'cost-calculator-builder' ),
                'alias'       => 'datepicker',
                'type'        => 'date-picker',
                'icon'        => 'fas fa-calendar-alt',
                'description' => 'date picker fields'
            ],
            [
                'name'        => __( 'Range Button', 'cost-calculator-builder' ),
                'alias'       => 'range',
                'type'        => 'range-button',
                'icon'        => 'fas fa-exchange-alt',
                'description' => 'range slider'
            ],
            [
                'name'        => __( 'Drop Down', 'cost-calculator-builder' ),
                'alias'       => 'drop-down',
                'type'        => 'drop-down',
                'icon'        => 'fas fa-chevron-down',
                'description' => 'drop-down fields'
            ],
            [
                'name'        => __( 'Text', 'cost-calculator-builder' ),
                'alias'       => 'text-area',
                'type'        => 'text-area',
                'icon'        => 'fas fa-font',
                'description' => 'text fields'
            ],
            [
                'name'        => __( 'Html', 'cost-calculator-builder' ),
                'alias'       => 'html',
                'type'        => 'html',
                'icon'        => 'fas fa-code',
                'description' => 'html elements'
            ],
            [
                'name'        => __( 'Total', 'cost-calculator-builder' ),
                'alias'       => 'total',
                'type'        => 'total',
                'icon'        => 'fas fa-calculator',
                'description' => 'total fields'
            ],
            [
                'name'        => __( 'Line', 'cost-calculator-builder' ),
                'alias'       => 'line',
                'type'        => 'line',
                'icon'        => 'fas fa-ruler-horizontal',
                'description' => 'horizontal ruler'
            ],
            [
                'name'        => __( 'Quantity', 'cost-calculator-builder' ),
                'alias'       => 'quantity',
                'type'        => 'quantity',
                'icon'        => 'fas fa-hand-peace',
                'description' => 'quantity fields'
            ],
            [
                'name'        => __( 'Multi Range', 'cost-calculator-builder' ),
                'alias'       => 'multi-range',
                'type'        => 'multi-range',
                'icon'        => 'fas fa-exchange-alt',
                'description' => 'multi-range field'
            ],
            [
                'name'        => __( 'Toggle Button', 'cost-calculator-builder' ),
                'alias'       => 'toggle',
                'type'        => 'toggle',
                'icon'        => 'fas fa-toggle-on',
                'description' => 'toggle fields'
            ],
            [
                'name'              => __( 'File Upload', 'cost-calculator-builder' ),
                'alias'             => 'file-upload',
                'type'              => 'file-upload',
                'icon'              => 'fas fa-cloud-upload-alt',
                'description'       => 'file upload field',
                'formats'           => self::getFileFieldFormatsBasedOnPermission(),
            ],

            [
                'name'        => __( 'Drop Down With Image', 'cost-calculator-builder' ),
                'alias'       => 'drop-down-with-image',
                'type'        => 'drop-down-with-image',
                'icon'        => 'far fa-image',
                'description' => 'drop-down with image field'
            ],
        ];
    }

    private static function getFileFieldFormatsBasedOnPermission() {
        /** check is allowed all */
        if ( defined('ALLOW_UNFILTERED_UPLOADS') && ALLOW_UNFILTERED_UPLOADS !== false ){
            return self::$fileFieldFormats;
        }

        /** check with wp allowed mime types */
        $allowedFileMimeTypes = get_allowed_mime_types();
        $allowedFileTypes     = array_keys($allowedFileMimeTypes);

        $allowedTypes = [];
        foreach ($allowedFileTypes as $type) {
            $allowedTypes = array_merge($allowedTypes, explode('|', $type) );
        }

        foreach (self::$fileFieldFormats as $fieldFormat) {
            $allowed = true;
            foreach (explode('/', $fieldFormat) as $subType ){
                if ( !in_array($subType, $allowedTypes) ) {
                    $allowed = false;
                }
            }

            if ( !$allowed && ($key = array_search($fieldFormat, self::$fileFieldFormats)) !== false ){
                unset(self::$fileFieldFormats[$key]);
            }
        }
        return self::$fileFieldFormats;
    }
}