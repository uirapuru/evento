<?php
namespace AppBundle\Command;

use AppBundle\Entity\Workshop;
use DateTime;
use Dende\Calendar\Domain\Calendar\Event;

/**
 * Class CreateLesson
 * @package AppBundle\Command
 */
class CreateLesson implements LessonCommandInterface
{
    /** @var string */
    public $title;

    /** @var string */
    public $description;

    /** @var DateTime */
    public $startDate;

    /** @var DateTime */
    public $endDate;

    /** @var string */
    public $address;

    /** @var string */
    public $city;

    /** @var  Workshop */
    public $workshop;

    /** @var Event */
    public $event;
}