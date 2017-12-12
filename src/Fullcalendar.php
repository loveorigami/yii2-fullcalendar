<?php

namespace lo\widgets\fullcalendar;

use lo\core\helpers\ArrayHelper;
use lo\widgets\fullcalendar\assets\FullcalendarAsset;
use lo\widgets\fullcalendar\assets\ThemeAsset;
use lo\widgets\fullcalendar\dto\SelectModalDto;
use lo\widgets\fullcalendar\presets\IPreset;
use lo\widgets\modal\ModalAjax;
use Yii;
use yii\base\Widget;
use yii\helpers\Json;
use yii\web\JsExpression;

/**
 * Class Fullcalendar
 * @package lo\widgets\fullcalendar
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Fullcalendar extends Widget
{
    /** @var  string */
    public $presetClass;

    /**
     * @var array  The fullcalendar options, for all available options check http://fullcalendar.io/docs/
     */
    public $clientOptions = [
        'weekends' => true,
        'default' => 'timelineDay',
        'editable' => false,
    ];

    /** @var boolean  Determines whether or not to include the gcal.js */
    public $googleCalendar = false;

    /**
     * @var array
     * Possible header keys
     * - center
     * - left
     * - right
     * Possible options:
     * - title
     * - prevYear
     * - nextYear
     * - prev
     * - next
     * - today
     * - basicDay
     * - agendaDay
     * - basicWeek
     * - agendaWeek
     * - month
     */
    public $header = [
        'center' => 'title',
        'left' => 'prev,next, today',
        'right' => 'timelineDay,timelineWeek,timelineMonth,timelineYear',
    ];

    /** @var string  Text to display while the calendar is loading */
    public $loading = 'Please wait, calendar is loading';

    /**
     * @var array  Default options for the id and class HTML attributes
     */
    public $options = [
        'id' => 'calendar',
        'class' => 'fullcalendar',
    ];

    /**
     * @var boolean  Whether or not we need to include the ThemeAsset bundle
     */
    public $theme = false;

    /**
     * @var string
     * Example select expression : JsExpression
     * $JSEventSelect = <<<EOF
     * function(start, end, jsEvent, view) {
     *      alert (start + end);
     * }
     * EOF;
     */
    public $selectExpression;

    /**
     * @var array
     */
    public $selectModalOptions = [
        'id' => 'modal-select',
        'headerLabel' => 'Model Header Label',
        'modal-size' => 'modal-lg',
        'url' => ''
    ];

    /**
     * @var SelectModalDto
     */
    protected $selectModalDto;

    /**
     * Modal Form, 'CLICK SHOW' for fullcalendar schedule
     * @var array
     * Example click views : JsExpression
     * $JSEventClick = <<<EOF
     *     function( start, end, jsEvent, view) {
     *         $('#modal-click').modal('show'); // Id of Modal click
     *    }
     * EOF;
     */

    protected $calendar;

    /**
     * Always make sure we have a valid id and class for the Fullcalendar widget
     */
    public function init()
    {
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }

        if (!isset($this->options['class'])) {
            $this->options['class'] = 'fullcalendar';
        }

        $this->selectModalDto = new SelectModalDto($this->selectModalOptions);

        $this->calendar = "jQuery('#{$this->options['id']}')";


        parent::init();
    }

    /**
     * Load the options and start the widget
     */
    public function run()
    {
        $this->registerAssets();

        $this->view->registerJs(implode("\n", [
            $this->calendar . ".fullCalendar({$this->getClientOptions()});",
        ]));

        return $this->render('calendar', [
            'options' => $this->options,
            'loading' => $this->loading,
            'selectModal' => $this->getSelectModal()
        ]);
    }

    /**
     * register assets
     */
    protected function registerAssets()
    {
        $assets = FullcalendarAsset::register($this->view);

        if ($this->theme === true) {
            // Register the theme
            ThemeAsset::register($this->view);
        }

        if (isset($this->options['language'])) {
            $assets->language = $this->options['language'];
        }

        $assets->googleCalendar = $this->googleCalendar;
    }

    /**
     * @return string
     * Returns an JSON array containing the fullcalendar options,
     * all available callbacks will be wrapped in JsExpressions objects if they're set
     */
    private function getClientOptions()
    {
        $options = $this->clientOptions;

        $options['header'] = $this->header;

        $options['loading'] = new JsExpression("function(isLoading, view) {
			{$this->calendar}.find('.fc-loading').toggle(isLoading);
        }");

        if (!isset($options['select'])) {
            $options['select'] = $this->getSelectExpression();
        }

        return Json::encode($options);
    }

    /**
     * @return null|string
     */
    protected function getSelectModal()
    {
        if (!$this->selectModalDto->hasUrl()) {
            return null;
        }

        return ModalAjax::widget([
            'url' => $this->selectModalDto->getUrl(),
            'size' => $this->selectModalDto->getSize(),
            'header' => $this->selectModalDto->getHeader(),
            'options' => [
                "id" => $this->selectModalDto->getId(),
                'tabindex' => false,
                'class' => 'header-primary',
            ],
            'events' => [
                ModalAjax::EVENT_MODAL_SUBMIT => new JsExpression("
                    function(event, data, status, xhr) {
                        if(status){
                            {$this->calendar}.fullCalendar('refetchEvents');
                            $(this).modal('toggle');
                        }
                    }
                ")
            ],
            'clientOptions' => ['backdrop' => 'static', 'keyboard' => FALSE]
        ]);
    }

    /**
     * @return null|JsExpression
     */
    protected function getSelectExpression()
    {
        if ($this->selectExpression && !$this->selectModalDto->hasUrl()) {
            return ($this->selectExpression instanceof JsExpression) ? $this->selectExpression : new JsExpression($this->selectExpression);
        }

        if ($this->selectModalDto->hasUrl()) {
            return $this->getSelectModalExpression();
        }

        return null;
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
                    settings['url'] = modalUrl('" . $this->selectModalDto->getUrl() . "', 
                        {
                            'start':tgl1, 
                            'end':tgl2
                        }
                    );
                })
                .modal('show');
            }
		");
    }


    protected function getDropExpression()
    {
        //input Event Drop
        $options['eventDrop'] = new JsExpression("function(event, element, view){
					var child = event.parent;
					var status = event.status;
					var dateTime2 = new Date(event.end);
					var dateTime1 = new Date(event.start);
					var tgl1 = moment(dateTime1).format('YYYY-MM-DD');
					var tgl2 = moment(dateTime2).subtract(1, 'days').format('YYYY-MM-DD');
					var id = event.id;
					if(child != 0 && status != 1){
						$.get('....',{'id':id,'start':tgl1,'end':tgl2});
					}
				}
			");
    }


    /**
     * Creates a widget instance and runs it.
     * The widget rendering result is returned by this method.
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @return string the rendering result of the widget.
     * @throws \Exception
     */
    public static function widget($config = [])
    {
        ob_start();
        ob_implicit_flush(false);
        $preset = [];
        if (isset($config['presetClass'])) {
            /** @var IPreset $obj */
            $obj = new $config['presetClass']();
            if ($obj instanceof IPreset) {
                $preset = $obj::config();
            }
            $config = ArrayHelper::merge($preset, $config);
        }

        try {
            /* @var $widget Widget */
            $config['class'] = get_called_class();
            $widget = Yii::createObject($config);
            $out = '';
            if ($widget->beforeRun()) {
                $result = $widget->run();
                $out = $widget->afterRun($result);
            }
        } catch (\Exception $e) {
            // close the output buffer opened above if it has not been closed already
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            throw $e;
        }

        return ob_get_clean() . $out;
    }
}
