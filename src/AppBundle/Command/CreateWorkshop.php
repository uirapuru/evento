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

    /** @var ArrayCollection|LessonCommandInterface[] */
    public $lessons;

    /** @var string */
    public $url;

    /** @var string */
    public $email;

    /** @var string */
    public $phone;

    /** @var Calendar */
    public $calendar;
}