<?php
namespace AppBundle\Event;

use AppBundle\Entity\Workshop;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class WorkshopBeforeInsert
 * @package AppBundle\Event
 */
class WorkshopBeforeInsert extends Event
{
    /** @var Workshop */
    private $workshop;

    /**
     * WorkshopBeforeInsert constructor.
     * @param Workshop $workshop
     */
    public function __construct(Workshop $workshop)
    {
        $this->workshop = $workshop;
    }

    /**
     * @return Workshop
     */
    public function getWorkshop()
    {
        return $this->workshop;
    }
}