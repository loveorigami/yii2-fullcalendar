<?php

namespace lo\widgets\fullcalendar\assets;

use yii\web\AssetBundle;

/**
 * Class DesignAsset
 * @package lo\widgets\fullcalendar\assets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class DesignAsset extends AssetBundle
{
    /** @var  array The CSS file for the print style */
    public $css = [
        'css/design.css',
    ];

    /** @var  array The CSS file for the print style */
    public $js = [
        'js/design.js',
    ];

    /** @var  array List of the dependencies this assets bundle requires */
    public $depends = [
        'lo\widgets\fullcalendar\assets\FullcalendarAsset',
    ];

    /**
     * Initializes the bundle.
     * Set publish options to copy only necessary files (in this case css and font folders)
     * @codeCoverageIgnore
     */
    public function init()
    {
        parent::init();
        $this->sourcePath = __DIR__ . "/src";
    }
}