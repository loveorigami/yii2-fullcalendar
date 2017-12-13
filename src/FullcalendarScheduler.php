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
    protected function getCreateUrlExpression()
    {
        return new JsExpression("
            function(start,end,jsEvent,view,resource){
                var dateTime2 = new Date(end);
                var dateTime1 = new Date(start);
                var tgl1 = moment(dateTime1).format();
                var tgl2 = moment(dateTime2).subtract(1, 'days').format();
                var rid = resource.id;

                $('#" . $this->createDto->getId() . "')
                .on('" . ModalAjax::EVENT_BEFORE_SHOW . "', function(event, xhr, settings) {
                    settings.url = modalUrl('" . $this->createDto->getUrl() . "', 
                        {
                            'start':tgl1, 
                            'end':tgl2,
                            'rid':rid
                        }
                    );
                })
                .modal('show');
            }
		");
    }
}
