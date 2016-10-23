<?php
/**
 * Created by PhpStorm.
 * User: piyush sharma
 * Date: 01-12-2015
 * Time: 21:26
 */

namespace Events;


use Mailgun\Mailgun;

if(!defined("EVENT_PATH"))
    define("EVENT_PATH","../Event/");

if(!defined('VENDOR_PATH'))
    define("VENDOR_PATH","../vendor/");

include_once(EVENT_PATH."Contracts/IEvent.php");
include_once(EVENT_PATH."Contracts/IEventHandler.php");
require VENDOR_PATH.'autoload.php';


class CountryFilterEventHandler implements IEventHandler{

    private $countryFilterEvent;

    public function __construct(CountryFilterEvent $countryFilterEvent)
    {
        $this->countryFilterEvent = $countryFilterEvent;
    }

    /**
     *Handler to handle when an Event is raised
     */
    public function handleEvent()
    {
        switch($this->countryFilterEvent->eventRaised)
        {
            case CountryFilterEvent::COUNTRY_FILTER_NOT_WORKING:
                $this->countryNotFoundHandler($this->countryFilterEvent->survey_id);
                break;
        }
    }

    /**
     *Handler to an event, when a country is not found in the Country Filter of a survey.
     */
    public function countryNotFoundHandler($survey_id)
    {
        # Instantiate the client.
        $mgClient = new Mailgun('key-ef43176cf355dd4eab5649acb1c89d0c');
        $domain = "hopstek.com";

        $to="piyush_sharma_it@yahoo.in";
        $subject="Country Filter is not working";
        $message = "Hi,\r\n\r\n".
            "One of the survey's country filter is not working correctly.\r\n\r\n".
            "Visit to the survey by clicking on below URL:\r\n\r\n".
            "http://".$_SERVER['HTTP_HOST']."/".str_replace("\\","/",substr(getcwd(), strlen($_SERVER['DOCUMENT_ROOT'])))."/view_survey_details.php?survey_id=".$survey_id."\r\n\r\n".
            "Regards,\r\nSMT";

        # Make the call to the client.
        $result = $mgClient->sendMessage($domain, array(
            'from'    => 'SMT <smt@hopstek.com>',
            'to'      => $to,
            'subject' => $subject,
            'text'    => $message
        ));
    }
}