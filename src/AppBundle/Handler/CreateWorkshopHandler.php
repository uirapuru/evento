<?php
namespace AppBundle\Handler;

use AppBundle\Command\CreateLesson;
use AppBundle\Command\CreateWorkshop;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\WorkshopRepository;
use AppBundle\Event\WorkshopBeforeInsert;
use AppBundle\Event\WorkshopEvents;
use AppBundle\Factory\LessonFactory;
use AppBundle\Factory\WorkshopFactory;
use Carbon\Carbon;
use Dende\Calendar\Application\Command\CreateEventCommand;
use Dende\Calendar\Application\Factory\EventFactory;
use Dende\Calendar\Application\Factory\EventFactoryInterface;
use Dende\Calendar\Application\Factory\OccurrenceFactory;
use Dende\Calendar\Domain\Calendar;
use Dende\Calendar\Domain\Calendar\Event;
use Dende\Calendar\Domain\Calendar\Event\EventType;
use Dende\CalendarBundle\Repository\ORM\CalendarRepository;
use Dende\CalendarBundle\Repository\ORM\EventRepository;
use Exception;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class CreateWorkshopHandler
 * @package AppBundle\Handler
 */
class CreateWorkshopHandler
{
    /** @var  WorkshopRepository */
    private $workshopRepository;

    /** @var  CalendarRepository */
    private $calendarRepository;

    /** @var  EventRepository */
    private $eventRepository;

    /** @var WorkshopFactory */
    private $workshopFactory;

    /** @var  LessonFactory */
    private $lessonFactory;

    /** @var  EventFactoryInterface|EventFactory */
    private $eventFactory;

    /** @var  OccurrenceFactory */
    private $occurrenceFactory;

    /**
     * CreateWorkshopHandler constructor.
     * @param WorkshopRepository $workshopRepository
     * @param CalendarRepository $calendarRepository
     * @param EventRepository $eventRepository
     * @param WorkshopFactory $workshopFactory
     * @param LessonFactory $lessonFactory
     * @param EventFactory|EventFactoryInterface $eventFactory
     * @param OccurrenceFactory $occurrenceFactory
     */
    public function __construct(WorkshopRepository $workshopRepository, CalendarRepository $calendarRepository, EventRepository $eventRepository, WorkshopFactory $workshopFactory, LessonFactory $lessonFactory, $eventFactory, OccurrenceFactory $occurrenceFactory)
    {
        $this->workshopRepository = $workshopRepository;
        $this->calendarRepository = $calendarRepository;
        $this->eventRepository = $eventRepository;
        $this->workshopFactory = $workshopFactory;
        $this->lessonFactory = $lessonFactory;
        $this->eventFactory = $eventFactory;
        $this->occurrenceFactory = $occurrenceFactory;
    }


    /**
     * @param CreateWorkshop $command
     * @throws Exception
     */
    public function handle(CreateWorkshop $command){

        if(!is_array($command->lessons) || count($command->lessons) === 0) {
            throw new Exception("Lessons collection can't be empty!");
        }

        $calendar = new Calendar(null, $command->title);
        $command->calendar = $calendar;

        $workshop = $this->workshopFactory->createFromCommand($command);

        /** @var CreateLesson $lessonCommand */
        foreach($command->lessons as $lessonCommand) {

            $duration = Carbon::instance($lessonCommand->startDate)->diffInMinutes(Carbon::instance($lessonCommand->endDate), true);

            $eventCommand = new CreateEventCommand();
            $eventCommand->calendar = $calendar;
            $eventCommand->duration = $duration;
            $eventCommand->startDate = $lessonCommand->startDate;
            $eventCommand->endDate = Carbon::instance($lessonCommand->startDate)->addMinutes($duration);
            $eventCommand->repetitionDays = [];
            $eventCommand->type = EventType::TYPE_SINGLE;

            $event = $this->eventFactory->createFromCommand($eventCommand);
            $occurrences = $this->occurrenceFactory->generateCollectionFromEvent($event);
            $calendar->events()->add($event);

            if (count($occurrences) ==! 1) {
                throw new Exception('Could not generate occurrences from event');
            }

            $event->setOccurrences($occurrences);

            $lessonCommand->workshop = $workshop;
            $lessonCommand->event = $event;
            $lesson = $this->lessonFactory->createFromCommand($lessonCommand);

            $workshop->addLesson($lesson);
        }

        $this->calendarRepository->insert($calendar);
        $this->workshopRepository->insert($workshop);
    }
}