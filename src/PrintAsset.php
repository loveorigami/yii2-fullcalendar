<?php

namespace lo\widgets\fullcalendar;

/**
 * Class PrintAsset
 * @package edofre\fullcalendarscheduler
 */
class PrintAsset extends \yii\web\AssetBundle
{
    /** @var  array The CSS file for the print style */
    public $css = [
        'fullcalendar.print.css',
    ];

    /** @var  array The CSS options */
    public $cssOptions = [
        'media' => 'print',
    ];

    /** @var  string Npm path for the print settings */
    public $sourcePath = '@bower/fullcalendar/dist';
}

