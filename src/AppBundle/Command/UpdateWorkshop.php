<?php
namespace AppBundle\Command;

use AppBundle\Entity\Workshop;

/**
 * Class UpdateWorkshop
 * @package AppBundle\Command
 */
class UpdateWorkshop implements WorkshopCommandInterface
{
    public $id;
    public $title;
    public $description;
    public $startDate;
    public $endDate;
    public $lessons;
    public $url;
    public $email;
    public $phone;
    public $city;
    public $slug;

    /**
     * UpdateWorkshop constructor.
     * @param Workshop $workshop
     */
    public function __construct(Workshop $workshop)
    {
        $this->id = $workshop->getId();
        $this->title = $workshop->getTitle();
        $this->description = $workshop->getDescription();
        $this->startDate = $workshop->getStartDate();
        $this->endDate = $workshop->getEndDate();
        $this->city = $workshop->getCity();
        $this->lessons = $workshop->getLessons();
        $this->phone = $workshop->getPhone();
        $this->url = $workshop->getUrl();
        $this->email = $workshop->getEmail();
    }
}