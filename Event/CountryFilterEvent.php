<?php
/**
 * Created by PhpStorm.
 * User: piyush sharma
 * Date: 01-12-2015
 * Time: 21:23
 */

namespace Events;


if(!defined("EVENT_PATH"))
    define("EVENT_PATH","../Event/");

include_once(EVENT_PATH."Contracts/IEvent.php");
include_once(EVENT_PATH."Contracts/IEventHandler.php");

class CountryFilterEvent implements IEvent {

    const COUNTRY_FILTER_NOT_WORKING = "country_filter_not_working";

    public $survey_id=null;

    public $eventRaised = null;

    public function __construct($survey_id,$eventRaised)
    {
        $this->survey_id = $survey_id;
        $this->eventRaised = $eventRaised;
    }

    public function raiseEvent(IEventHandler $eventHandler)
    {
        $eventHandler->handleEvent();
    }
}