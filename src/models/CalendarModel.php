<?php

namespace lo\widgets\fullcalendar\models;

use yii\base\Model;

/**
 * Class CalendarModel
 * @package lo\widgets\fullcalendar\models
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class CalendarModel extends Model
{
    /**
     * @return array
     */
    public function fields()
    {
        $fields = parent::fields();
        foreach ($fields as $field) {
            // If the attribute is not set don't return it in the response
            if (is_null($this->$field)) {
                unset($fields[$field]);
            }
        }
        return $fields;
    }
}