<?php
namespace AppBundle\Entity;

use Dende\Calendar\Domain\Calendar\Event;

class Lesson
{
    /** @var string */
    private $id;

    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var string */
    private $address;

    /** @var string */
    private $city;

    /** @var float */
    private $latitude;

    /** @var float */
    private $longitude;

    /** @var Workshop */
    private $workshop;

    /** @var Event */
    private $event;

    /**
     * Lesson constructor.
     * @param $id
     * @param $title
     * @param $description
     * @param $address
     * @param $city
     * @param $latitude
     * @param $longitude
     * @param $workshop
     * @param $event
     */
    public function __construct($id = null, $title = null, $description = null, $address = null, $city = null, $latitude = null, $longitude = null, Workshop $workshop = null, Event $event = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->address = $address;
        $this->city = $city;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->workshop = $workshop;
        $this->event = $event;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return mixed
     */
    public function getWorkshop()
    {
        return $this->workshop;
    }

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    public function setWorkshop(Workshop $workshop)
    {
        $this->workshop = $workshop;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate(){
        return $this->getEvent()->startDate();
    }

    /**
     * @return \DateTime
     */
    public function getEndDate(){
        return $this->getEvent()->endDate();
    }
}