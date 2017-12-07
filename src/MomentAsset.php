<?php

namespace lo\widgets\fullcalendar;

/**
 * Class MomentAsset
 */
class MomentAsset extends \yii\web\AssetBundle
{
    /** @var  array  The javascript file for the Moment library */
    public $js = [
        'moment.js',
    ];
    /** @var  string  The location of the Moment.js library */
    public $sourcePath = '@bower/moment';
}