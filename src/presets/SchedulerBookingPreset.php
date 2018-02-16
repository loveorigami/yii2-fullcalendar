<?php

namespace lo\widgets\fullcalendar\presets;

class SchedulerBookingPreset implements IPreset
{
    /**
     * @return array
     */
    public static function config(): array
    {
        return [
            'header' => [
                'left' => 'today prev,next',
                'center' => 'title',
                'right' => 'timelineMonth,timelineYear'
            ],
            'clientOptions' => [
                'schedulerLicenseKey' => 'GPL-My-Project-Is-Open-Source',
                'editable' => true, // enable draggable events
                'aspectRatio' => 1.5,
                'scrollTime' => '00:00', // undo default 6am scrollTime
                'eventLimit' => 1,
                'defaultView' => 'timelineMonth',
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
                'eventResourceEditable' => false,
                'selectable' => true,
                'selectHelper' => true,
                'resourceLabelText' => 'Rooms',
                'resourceAreaWidth' => '24%',
            ]
        ];
    }
}
