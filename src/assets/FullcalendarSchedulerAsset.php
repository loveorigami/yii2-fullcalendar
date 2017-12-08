<?php

namespace lo\widgets\fullcalendar\assets;

use yii\web\AssetBundle;

/**
 * Class FullcalendarSchedulerAsset
 * @package lo\widgets\fullcalendar\assets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class FullcalendarSchedulerAsset extends AssetBundle
{
    /** @var  string  The location of the Moment.js library */
    public $sourcePath = '@bower/fullcalendar-scheduler/dist';

    /** @var  array  The javascript file for the Moment library */
    public $js = [
        'scheduler.js',
    ];

    /** @var  array*/
    public $css = [
        'scheduler.css',
    ];

    /** @var  array List of the dependencies this assets bundle requires */
    public $depends = [
        'lo\widgets\fullcalendar\assets\FullcalendarAsset',
    ];
}
