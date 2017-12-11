<?php
/**
 * @var array $options
 * @var string $loading
 * @var string $selectModal
 */
use yii\helpers\Html;

echo Html::beginTag('div', $options) . "\n";
echo Html::beginTag('div', ['class' => 'fc-loading', 'style' => 'display:none;']);
echo Html::encode($loading);
echo Html::endTag('div') . "\n";
echo Html::endTag('div') . "\n";

echo $selectModal;
