<?php

namespace lo\widgets\fullcalendar\presets;

use lo\widgets\fullcalendar\helpers\CalendarHelper;

/**
 * Class CalendarDemoPreset
 * @package lo\widgets\fullcalendar\presets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class CalendarDemoPreset implements IPreset
{
    /**
     * @return array
     */
    public static function config(): array
    {
        return [
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
            'header' => [
                'left' => 'today prev,next',
                'center' => 'title',
                'right' => 'agendaWeek,month'
            ],
            'createExpression' => 'function(start, end, jsEvent, view) { console.log(jsEvent); }',
            'updateExpression' => 'function(calEvent){ alert(calEvent.id); }',
            'deleteExpression' => 'function(calEvent){ alert(calEvent.id); }',
            'afterRenderExpression' => 'function(calEvent, element){ alert(calEvent.id); }',
            'clientOptions' => [
                'now' => CalendarHelper::now(),
                'events' => CalendarHelper::events(),
                'editable' => true, // enable draggable events
                'aspectRatio' => 1.8,
                'scrollTime' => '00:00', // undo default 6am scrollTime
                'eventLimit' => 4,
                'defaultView' => 'month',
                'views' => [
                    'timelineMonth' => [
                        'type' => 'timeline',
                    ],
                ],
                'contentHeight' => 400,
                'eventOverlap' => false,
                'selectable' => true,
                'selectHelper' => true,
            ]
        ];
    }
}
