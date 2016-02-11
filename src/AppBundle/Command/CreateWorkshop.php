<?php
namespace AppBundle\Command;

use Dende\Calendar\Domain\Calendar;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class CreateWorkshop
 * @package AppBundle\Command
 */
class CreateWorkshop implements WorkshopCommandInterface
{
    /** @var string */
    public $title;

    /** @var string */
    public $description;

    /** @var  \DateTime */
    public $startDate;

    /** @var  \DateTime */
    public $endDate;

    /** @var ArrayCollection|LessonCommandInterface[] */
    public $lessons;

    /** @var string */
    public $url;

    /** @var string */
    public $email;

    /** @var string */
    public $phone;

    /** @var string */
    public $city;

    /** @var Calendar */
    public $calendar;
}