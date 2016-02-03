<?php
namespace AppBundle\Entity;

class Lesson
{
    private $id;
    private $title;
    private $description;
    private $address;
    private $city;
    private $latitude;
    private $longitude;
    private $startDate;
    private $endDate;
    private $workshop;
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
     * @param $startDate
     * @param $endDate
     * @param $workshop
     * @param $event
     */
    public function __construct($id = null, $title = null, $description = null, $address = null, $city = null, $latitude = null, $longitude = null, $startDate = null, $endDate = null, $workshop = null, Event $event = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->address = $address;
        $this->city = $city;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
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
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @return mixed
     */
    public function getWorkshop()
    {
        return $this->workshop;
    }

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }
}