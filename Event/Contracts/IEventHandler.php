<?php
/**
 * Created by PhpStorm.
 * User: piyush sharma
 * Date: 01-12-2015
 * Time: 20:56
 */

namespace Events;


interface IEventHandler {

    //Function to handle an Event
    public function handleEvent();

}