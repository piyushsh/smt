<?php
/**
 * Created by PhpStorm.
 * User: piyush sharma
 * Date: 01-12-2015
 * Time: 20:54
 */

namespace Events;


interface IEvent {

    //Raise Event
    public function raiseEvent(IEventHandler $eventHandler);
}