<?php
namespace AppBundle\Handler;

use AppBundle\Command\UpdateWorkshop;
use AppBundle\Entity\LessonRepository;
use AppBundle\Entity\Workshop;
use AppBundle\Entity\WorkshopRepository;
use AppBundle\Generator\WorkshopSlugGenerator;
use Exception;

/**
 * Class CreateWorkshopHandler
 * @package AppBundle\Handler
 */
class UpdateLessonCollectionHandler
{
    /** @var  LessonRepository */
    private $lessonRepository;

    /**
     * UpdateLessonCollectionHandler constructor.
     * @param LessonRepository $lessonRepository
     */
    public function __construct(LessonRepository $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    /**
     * @param UpdateWorkshop $command
     * @throws Exception
     */
    public function handle(UpdateWorkshop $command)
    {
        if (!is_array($command->lessons) || count($command->lessons) === 0) {
            throw new Exception("Lessons collection can't be empty!");
        }

    }
}