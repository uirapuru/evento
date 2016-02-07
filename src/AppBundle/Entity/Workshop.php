<?php
namespace AppBundle\Entity;
use AppBundle\Command\UpdateWorkshop;
use Dende\Calendar\Domain\Calendar;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Workshop
 * @package AppBundle\Entity
 */
class Workshop
{
    private $id;
    private $title;
    private $description;
    private $startDate;
    private $endDate;
    private $lessons;
    private $user;
    private $url;
    private $city;
    private $group;
    private $organizatorName;
    private $email;
    private $phone;
    private $slug;
    private $calendar;


    /**
     * Workshop constructor.
     * @param $id
     * @param $title
     * @param $description
     * @param $startDate
     * @param $endDate
     * @param $lessons
     * @param $user
     * @param $url
     * @param $email
     * @param $phone
     */
    public function __construct($id = null, $title = null, $description = null, $startDate = null, $endDate = null, Collection $lessons = null, $user = null, $url = null, $email = null, $phone = null, $city = null, $slug = null, Calendar $calendar = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->lessons = $lessons;
        $this->user = $user;
        $this->url = $url;
        $this->email = $email;
        $this->phone = $phone;
        $this->city = $city;
        $this->slug = $slug;
        $this->calendar = $calendar;
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
     * @return ArrayCollection
     */
    public function getLessons()
    {
        return $this->lessons;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param Lesson $lesson
     */
    public function addLesson(Lesson $lesson) {
        $lesson->setWorkshop($this);
        $this->lessons->add($lesson);
    }

    /**
     * @param Lesson $lesson
     */
    public function removeLesson(Lesson $lesson) {
        $this->lessons->remove($lesson);
    }

    /**
     * @return null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @return null
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return Calendar
     */
    public function getCalendar()
    {
        return $this->calendar;
    }

    /**
     * @param UpdateWorkshop $command
     */
    public function updateWithCommand(UpdateWorkshop $command)
    {
        $this->city = $command->city;
        $this->description = $command->description;
        $this->email = $command->email;
        $this->endDate = $command->endDate;
        $this->startDate = $command->startDate;
        $this->url = $command->url;
        $this->title = $command->title;
    }

    public function updateSlug($slug) {
        $this->slug = $slug;
    }
}