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
    /** @var string */
    private $id;
    /** @var string */
    private $title;
    /** @var string */
    private $description;
    /** @var Collection|Lesson[] */
    private $lessons;
    private $user;
    /** @var string */
    private $url;
    private $group;
    private $organizatorName;
    /** @var string */
    private $email;
    /** @var string */
    private $phone;
    /** @var string */
    private $slug;
    /** @var Calendar */
    private $calendar;


    /**
     * Workshop constructor.
     * @param $id
     * @param $title
     * @param $description
     * @param $lessons
     * @param $user
     * @param $url
     * @param $email
     * @param $phone
     */
    public function __construct($id = null, $title = null, $description = null, Collection $lessons = null, $user = null, $url = null, $email = null, $phone = null, $slug = null, Calendar $calendar = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->lessons = $lessons;
        $this->user = $user;
        $this->url = $url;
        $this->email = $email;
        $this->phone = $phone;
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
        $this->url = $command->url;
        $this->title = $command->title;
    }

    /**
     * @param $slug
     */
    public function updateSlug($slug) {
        $this->slug = $slug;
    }

    public function getStartDate(){
        return min($this->getLessonsDates());
    }

    public function getEndDate(){
        return max($this->getLessonsDates());
    }

    private function getLessonsDates()
    {
        return array_map(function(Lesson $lesson){
            return $lesson->getStartDate();
        }, $this->getLessons()->toArray());
    }

    public function getCity(){
        return $this->lessons->first()->getCity();
    }
}