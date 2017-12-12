<?php

namespace lo\widgets\fullcalendar;

use lo\core\helpers\ArrayHelper;
use lo\widgets\fullcalendar\assets\FullcalendarAsset;
use lo\widgets\fullcalendar\assets\ThemeAsset;
use lo\widgets\fullcalendar\dto\DeleteDto;
use lo\widgets\fullcalendar\dto\UpdateDto;
use lo\widgets\fullcalendar\dto\CreateDto;
use lo\widgets\fullcalendar\presets\IPreset;
use lo\widgets\modal\ModalAjax;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
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
    public $createExpression;

    /**
     * @var string
     * Example select expression : JsExpression
     * $JSEventClick = <<<EOF
     * function(event) {
     *      alert (event.id);
     * }
     * EOF;
     */
    public $updateExpression;

    /**
     * @var string
     * Example select expression : JsExpression
     * $JSEventDelete = <<<EOF
     * function(event) {
     *      alert (event.id);
     * }
     * EOF;
     */
    public $deleteExpression;

    /**
     * @var array
     */
    public $createOptions = [
        'id' => 'modal-select',
        'headerLabel' => 'Model Header Label',
        'modal-size' => 'modal-lg',
        'url' => ''
    ];

    /**
     * @var array
     */
    public $updateOptions = [
        'id' => 'modal-update',
        'headerLabel' => 'Edit event',
        'modal-size' => 'modal-lg',
        'url' => ''
    ];

    /**
     * @var array
     */
    public $deleteOptions = [
        'id' => 'delete-event',
        'confirm' => 'Are you sure?',
        'url' => ''
    ];

    /**
     * @var CreateDto
     */
    protected $createDto;

    /**
     * @var UpdateDto
     */
    protected $updateDto;

    /**
     * @var DeleteDto
     */
    protected $deleteDto;

    /**
     * jQuery('#id');
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

        $this->createDto = new CreateDto($this->createOptions);
        $this->updateDto = new UpdateDto($this->updateOptions);
        $this->deleteDto = new DeleteDto($this->deleteOptions);

        $this->calendar = "jQuery('#{$this->options['id']}')";

        $this->registerAssets();
        $this->registerTranslations();

        parent::init();
    }

    /**
     * Load the options and start the widget
     */
    public function run()
    {
        $this->view->registerJs(implode("\n", [
            $this->calendar . ".fullCalendar({$this->getClientOptions()});",
        ]));

        return $this->render('calendar', [
            'options' => $this->options,
            'loading' => $this->loading,
            'createWrapper' => $this->getCreateWrapper(),
            'updateWrapper' => $this->getUpdateWrapper()
        ]);
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
            $options['select'] = $this->getCreateExpression();
        }

        if (!isset($options['eventClick'])) {
            $options['eventClick'] = $this->getUpdateExpression();
        }

        return Json::encode($options);
    }

    /**
     * @return null|string
     */
    protected function getCreateWrapper()
    {
        if (!$this->createDto->hasUrl()) {
            return null;
        }

        return ModalAjax::widget([
            'url' => $this->createDto->getUrl(),
            'size' => $this->createDto->getSize(),
            'header' => $this->createDto->getHeader(),
            'options' => [
                "id" => $this->createDto->getId(),
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
    protected function getCreateExpression()
    {
        if ($this->createExpression && !$this->createDto->hasUrl()) {
            return ($this->createExpression instanceof JsExpression) ? $this->createExpression : new JsExpression($this->createExpression);
        }

        if ($this->createDto->hasUrl()) {
            return $this->getCreateUrlExpression();
        }

        return null;
    }

    /**
     * @return JsExpression
     */
    protected function getCreateUrlExpression()
    {
        return new JsExpression("
            function(start,end,jsEvent,view){
                var dateTime2 = new Date(end);
                var dateTime1 = new Date(start);
                var tgl1 = moment(dateTime1).format('YYYY-MM-DD');
                var tgl2 = moment(dateTime2).subtract(1, 'days').format('YYYY-MM-DD');

                $('#" . $this->createDto->getId() . "')
                .on('" . ModalAjax::EVENT_BEFORE_SHOW . "', function(event, xhr, settings) {
                    settings.url = modalUrl('" . $this->createDto->getUrl() . "', 
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

    /**
     * @return null|string
     */
    protected function getUpdateWrapper()
    {
        if (!$this->updateDto->hasUrl()) {
            return null;
        }

        return ModalAjax::widget([
            'url' => $this->updateDto->getUrl(),
            'size' => $this->updateDto->getSize(),
            'header' => $this->updateDto->getHeader(),
            'options' => [
                "id" => $this->updateDto->getId(),
                'tabindex' => false,
                'class' => 'header-primary',
            ],
            'footer' => $this->getDeleteButtons(),
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
    protected function getUpdateExpression()
    {
        if ($this->updateExpression && !$this->updateDto->hasUrl()) {
            return ($this->updateExpression instanceof JsExpression) ? $this->updateExpression : new JsExpression($this->updateExpression);
        }

        if ($this->updateDto->hasUrl()) {
            return $this->getUpdateUrlExpression();
        }

        return null;
    }

    /**
     * @return JsExpression
     */
    protected function getUpdateUrlExpression()
    {
        return new JsExpression("
            function(event){
                $('#" . $this->updateDto->getId() . "')
                .on('" . ModalAjax::EVENT_BEFORE_SHOW . "', function(e, xhr, settings) {
                    settings.url = modalUrl('" . $this->updateDto->getUrl() . "', {id:event.id});
                })
                .modal('show');
                " . $this->getDeleteExpression() . "
            }
		");
    }

    /**
     * @return string
     */
    protected function getDeleteButtons()
    {
        $btn[] = Html::tag('button', self::t('Cancel'), [
            'class' => 'btn',
            'data-dismiss' => 'modal',
            'aria-hidden' => true
        ]);
        $btn[] = Html::tag('button', self::t('Delete'), [
            'id' => $this->deleteDto->getId(),
            'class' => 'btn btn-danger',
            'data' => [
                'pjax' => 0,
            ]
        ]);

        return implode("\r\n", $btn);

    }

    /**
     * @return JsExpression
     */
    protected function getDeleteExpression()
    {
        $confirm = $this->deleteDto->getConfirm();

        if($this->deleteDto->hasUrl()){
                return new JsExpression("
                $('#{$this->deleteDto->getId()}').off().on('click', function(e){
                    e.preventDefault();
                    var url = modalUrl('" . $this->deleteDto->getUrl() . "', {id:event.id});
                    if(confirm('$confirm')){
                        $.ajax({
                            url: url,
                            method: 'post',
                            success: function() {
                                {$this->calendar}.fullCalendar('refetchEvents');
                                $('#{$this->updateDto->getId()}').modal('toggle');
                            }
                        });
                    }
                });
            ");
        } else {
            return new JsExpression(" 
                $('#{$this->deleteDto->getId()}').off().on('click', function(e){
                    alert('Deleted event id - ' + event.id);
                });
            ");
        }

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
     * Registration of translation class.
     */
    protected function registerTranslations()
    {
        Yii::$app->i18n->translations['lo/fullcalendar'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@lo/widgets/fullcalendar/messages',
            'fileMap' => [
                'lo/fullcalendar' => 'core.php',
            ],
        ];
    }

    /**
     * Translates a message to the specified language.
     *
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code (e.g. `en-US`, `en`). If this is null, the current of application
     * language.
     * @return string
     */
    public static function t($message, $params = [], $language = null)
    {
        return Yii::t('lo/fullcalendar', $message, $params, $language);
    }
}
