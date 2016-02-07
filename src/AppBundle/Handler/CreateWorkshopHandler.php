<?php
namespace AppBundle\Handler;

use AppBundle\Command\CreateLesson;
use AppBundle\Command\CreateWorkshop;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\LessonRepository;
use AppBundle\Entity\WorkshopRepository;
use AppBundle\Factory\LessonFactory;
use AppBundle\Factory\WorkshopFactory;
use Exception;

/**
 * Class CreateWorkshopHandler
 * @package AppBundle\Handler
 */
class CreateWorkshopHandler
{
    /** @var WorkshopFactory */
    private $workshopFactory;

    /** @var  WorkshopRepository */
    private $workshopRepository;

    /** @var  LessonFactory */
    private $lessonFactory;

    /**
     * CreateWorkshopHandler constructor.
     * @param $workshopFactory
     * @param $workshopRepository
     * @param $lessonFactory
     * @param $lessonRepository
     */
    public function __construct($workshopFactory, $workshopRepository, $lessonFactory)
    {
        $this->workshopFactory = $workshopFactory;
        $this->workshopRepository = $workshopRepository;
        $this->lessonFactory = $lessonFactory;
    }

    /**
     * @param CreateWorkshop $command
     * @throws Exception
     */
    public function handle(CreateWorkshop $command){
        if(!is_array($command->lessons) || count($command->lessons) === 0) {
            throw new Exception("Lessons collection can't be empty!");
        }

        $workshop = $this->workshopFactory->createFromCommand($command);

        /** @var Lesson $lesson */
        foreach($command->lessons as $lesson) {
            $workshop->addLesson($lesson);
        }

        // create calendar
        // create events
        // create occurrence
        // connect to lesson

        $this->workshopRepository->insert($workshop);
    }
}