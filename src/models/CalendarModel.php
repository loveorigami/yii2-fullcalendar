<?php

namespace lo\widgets\fullcalendar\models;

/**
 * Class CalendarModel
 */
class CalendarModel extends \yii\base\Model
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