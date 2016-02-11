<?php
namespace AppBundle\Subscriber;

use AppBundle\Event\WorkshopBeforeInsert;
use AppBundle\Event\WorkshopEvents;
use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Domain\Calendar;
use League\Tactician\CommandBus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class WorkshopEventSubscriber
 * @package AppBundle\Subscriber
 */
class WorkshopEventSubscriber implements EventSubscriberInterface
{

    /** @var  CommandBus */
    private $commandBus;

    /**
     * WorkshopEventSubscriber constructor.
     * @param CommandBus $commandBus
     */
    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            WorkshopEvents::BEFORE_WORKSHOP_INSERTED => ["beforeWorkshopInserted", 0]
        ];
    }

    /**
     * @param WorkshopBeforeInsert $event
     */
    public function beforeWorkshopInserted(WorkshopBeforeInsert $event)
    {
        $workshop = $event->getWorkshop();

        $command = new CreateEventCommand();

        foreach($workshop->getLessons() as $lesson) {
            $command->calendar = new Calendar(null, $workshop->getTitle());

        }


//        $calendar = new Calendar(null, $workshop->getTitle());
//
//        /** @var Lesson $lesson */
//        foreach($workshop->getLessons() as $lesson) {
//            $occurrences = $this->occurrence;
//            $event = $this->eventFactory->createFromArray([
//                'id'                     => $this->idGenerator->generateId(),
//                'title'                  => '',
//                'repetitions'            => new Repetitions([]),
//                'type'                   => new EventType(EventType::TYPE_SINGLE),
//                'occurrences'            => $occurrences,
//                'calendar'               => $calendar,
//                'duration'               => new Duration(0),
//                'startDate'              => $lesson->getStartDate(),
//                'endDate'                => $lesson->getEndDate(),
//            ]);
//            $calendar->events()->add($event);
//        }
    }
}
