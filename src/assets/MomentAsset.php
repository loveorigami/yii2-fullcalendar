<?php

namespace lo\widgets\fullcalendar\assets;

use yii\web\AssetBundle;

/**
 * Class MomentAsset
 * @package lo\widgets\fullcalendar\assets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class MomentAsset extends AssetBundle
{
    /** @var  array  The javascript file for the Moment library */
    public $js = [
        'moment.js',
    ];

    /** @var  string  The location of the Moment.js library */
    public $sourcePath = '@bower/moment';
}