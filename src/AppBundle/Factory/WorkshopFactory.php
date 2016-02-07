<?php
namespace AppBundle\Factory;

use AppBundle\Command\CreateWorkshop;
use AppBundle\Entity\Workshop;
use AppBundle\Generator\IdGeneratorInterface;
use Doctrine\Common\Collections\ArrayCollection;

class WorkshopFactory
{
    /** @var  IdGeneratorInterface */
    private $idGenerator;

    /** @var  GeneratorInterface */
    private $workshopSlugGenerator;

    /**
     * WorkshopFactory constructor.
     * @param $idGenerator
     */
    public function __construct(IdGeneratorInterface $idGenerator, $workshopSlugGenerator)
    {
        $this->idGenerator = $idGenerator;
        $this->workshopSlugGenerator = $workshopSlugGenerator;
    }

    public function createFromCommand(CreateWorkshop $command){
        return new Workshop(
            $this->idGenerator->generate(),
            $command->title,
            $command->description,
            $command->startDate,
            $command->endDate,
            new ArrayCollection(),
            null,
            $command->url,
            $command->email,
            $command->phone,
            $command->city,
            $this->workshopSlugGenerator->generate($command)
        );
    }
}