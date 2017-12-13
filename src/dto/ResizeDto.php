<?php
/**
 * Created by PhpStorm.
 * User: Lukyanov Andrey <loveorigami@mail.ru>
 * Date: 11.12.2017
 * Time: 12:15
 */

namespace lo\widgets\fullcalendar\dto;

use lo\widgets\fullcalendar\Fullcalendar;
use yii\helpers\Url;

/**
 * Class ResizeDto
 * @package lo\widgets\fullcalendar\dto
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ResizeDto
{
    protected $confirm = 'Are you sure?';
    protected $url = '';

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @return string
     */
    public function getConfirm()
    {
        return Fullcalendar::t($this->confirm);
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return (is_array($this->url)) ? Url::to($this->url) : $this->url;
    }

    /**
     * @return bool
     */
    public function hasUrl()
    {
        return $this->getUrl() != null;
    }
}