<?php
namespace AppBundle\Handler;

use AppBundle\Command\UpdateWorkshop;
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

    /** @var  WorkshopSlugGenerator */
    private $slugGenerator;

    /**
     * UpdateWorkshopHandler constructor.
     * @param $workshopRepository
     */
    public function __construct($workshopRepository, $slugGenerator)
    {
        $this->workshopRepository = $workshopRepository;
        $this->slugGenerator = $slugGenerator;
    }

    /**
     * @param UpdateWorkshop $command
     * @throws Exception
     */
    public function handle(UpdateWorkshop $command){
        if(!is_array($command->lessons) || count($command->lessons) === 0) {
            throw new Exception("Lessons collection can't be empty!");
        }

        /** @var Workshop $workshop */
        $workshop = $this->workshopRepository->findOneById($command->id);
        $workshop->updateWithCommand($command);

        $workshop->updateSlug($this->slugGenerator->generate($workshop));

        $this->workshopRepository->update($workshop);
    }
}