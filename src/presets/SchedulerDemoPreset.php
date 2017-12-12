<?php

namespace lo\widgets\fullcalendar\presets;

use lo\widgets\fullcalendar\helpers\CalendarHelper;

/**
 * Class SchedulerDemoPreset
 * @package lo\widgets\fullcalendar\presets
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class SchedulerDemoPreset implements IPreset
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
                'right' => 'timelineThreeDays,agendaWeek,month,timelineMonth,timelineYear'
            ],
            'clientOptions' => [
                'now' => CalendarHelper::now(),
                'events' => CalendarHelper::events(),
                'resources' => CalendarHelper::resources(),
                'schedulerLicenseKey' => 'GPL-My-Project-Is-Open-Source',
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
                'slotLabelFormat' => [
                    'YYYY MMMM', // top level of text
                    'D'        // lower level of text
                ],
                'eventOverlap' => false,
                'eventResourceEditable' => true,
                'selectable' => true,
                'selectHelper' => true,
                'resourceLabelText' => 'Rooms',
                'resourceAreaWidth' => '20%',
            ]
        ];
    }
}
