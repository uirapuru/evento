<?php
namespace AppBundle\Command;

use AppBundle\Entity\Workshop;

/**
 * Class UpdateWorkshop
 * @package AppBundle\Command
 */
class UpdateWorkshop extends CreateWorkshop
{
    public $id;

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
        $this->lessons = $workshop->getLessons(); /** @todo convert to UpdateLesson[] */
        $this->phone = $workshop->getPhone();
        $this->url = $workshop->getUrl();
        $this->email = $workshop->getEmail();
    }
}