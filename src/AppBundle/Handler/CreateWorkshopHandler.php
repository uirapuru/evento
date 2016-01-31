<?php
namespace AppBundle\Handler;

use AppBundle\Command\CreateLesson;
use AppBundle\Command\CreateWorkshop;
use AppBundle\Entity\LessonRepository;
use AppBundle\Entity\WorkshopRepository;
use AppBundle\Factory\LessonFactory;
use AppBundle\Factory\WorkshopFactory;

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
     */
    public function handle(CreateWorkshop $command){
        $workshop = $this->workshopFactory->createFromCommand($command);

        /** @var CreateLesson $lessonCommand */
        foreach($command->lessons as $lessonCommand) {
            $lessonCommand->workshop = $workshop;
            $lesson = $this->lessonFactory->createFromCommand($lessonCommand);
            $workshop->addLesson($lesson);
        }

        $this->workshopRepository->insert($workshop);
    }
}