<?php

namespace lo\widgets\fullcalendar\assets;

use yii\web\AssetBundle;

/**
 * Class PrintAsset
 * @package lo\widgets\fullcalendar\assets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class PrintAsset extends AssetBundle
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

