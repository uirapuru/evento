<?php
namespace AppBundle\Command;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\Workshop;
use AppBundle\Factory\LessonFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\PersistentCollection;

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
        $this->lessons = $this->convertLessons($workshop->getLessons());
        $this->phone = $workshop->getPhone();
        $this->url = $workshop->getUrl();
        $this->email = $workshop->getEmail();
    }

    private function convertLessons(Collection $lessons)
    {
        return array_map(function(Lesson $lesson){
            return LessonFactory::createUpdateCommand($lesson);
        }, $lessons->toArray());
    }
}