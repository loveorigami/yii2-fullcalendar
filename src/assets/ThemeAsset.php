<?php

namespace lo\widgets\fullcalendar\assets;

use yii\web\AssetBundle;

/**
 * Class ThemeAsset
 * @package lo\widgets\fullcalendar\assets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ThemeAsset extends AssetBundle
{
	/** @var array The dependencies for the ThemeAsset bundle */
	public $depends = [
		'yii\jui\JuiAsset',
	];
}