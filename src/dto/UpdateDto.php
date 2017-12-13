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

class UpdateDto
{
    protected $id = 'modal-update';
    protected $headerLabel = 'Edit event';
    protected $modalSize = 'modal-lg';
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getHeader()
    {
        return Fullcalendar::t($this->headerLabel);
    }

    /**
     * @return string
     */
    public function getSize()
    {
        return $this->modalSize;
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