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
class UpdateWorkshopHandler
{
    /** @var  WorkshopRepository */
    private $workshopRepository;

    /** @var  LessonRepository */
    private $lessonRepository;

    /** @var  WorkshopSlugGenerator */
    private $slugGenerator;

    /**
     * UpdateWorkshopHandler constructor.
     * @param WorkshopRepository $workshopRepository
     * @param LessonRepository $lessonRepository
     * @param WorkshopSlugGenerator $slugGenerator
     */
    public function __construct(WorkshopRepository $workshopRepository, LessonRepository $lessonRepository, WorkshopSlugGenerator $slugGenerator)
    {
        $this->workshopRepository = $workshopRepository;
        $this->lessonRepository = $lessonRepository;
        $this->slugGenerator = $slugGenerator;
    }

    /**
     * @param UpdateWorkshop $command
     * @throws Exception
     */
    public function handle(UpdateWorkshop $command){
        /** @var Workshop $workshop */
        $workshop = $this->workshopRepository->findOneById($command->id);
        $workshop->updateWithCommand($command);
        $workshop->updateSlug($this->slugGenerator->generate($workshop));

        $command->lessons;

        foreach($workshop->getLessons() as $existingLesson)
        {
            if(!in_array($existingLesson, $command->lessons)) {
                $this->lessonRepository->remove($existingLesson);
                $workshop->getLessons()->removeElement($existingLesson);
            }
        }

        $this->workshopRepository->update($workshop);
    }
}
