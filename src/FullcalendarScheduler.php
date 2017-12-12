<?php

namespace lo\widgets\fullcalendar;

use lo\widgets\fullcalendar\assets\FullcalendarSchedulerAsset;
use lo\widgets\modal\ModalAjax;
use yii\web\JsExpression;

/**
 * Class FullcalendarSchedulerWidget
 * @package lo\widgets\fullcalendar
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class FullcalendarScheduler extends Fullcalendar
{
    /**
     * register FullcalendarSchedulerAsset
     */
    protected function registerAssets()
    {
        parent::registerAssets();
        FullcalendarSchedulerAsset::register($this->view);
    }

    /**
     * @return JsExpression
     */
    protected function getSelectModalExpression()
    {
        return new JsExpression("
            function(start,end,jsEvent,view){
                var dateTime2 = new Date(end);
                var dateTime1 = new Date(start);
                var tgl1 = moment(dateTime1).format('YYYY-MM-DD');
                var tgl2 = moment(dateTime2).subtract(1, 'days').format('YYYY-MM-DD');
                
                $('#" . $this->selectModalDto->getId() . "')
                .on('" . ModalAjax::EVENT_BEFORE_SHOW . "', function(event, xhr, settings) {
                    settings['url'] = modalUrl('" . $this->selectModalDto->getUrl() . "', {'start':tgl1, 'end':tgl2});
                })
                .modal('show');
            }
		");
    }
}
