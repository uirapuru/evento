<?php
namespace AppBundle\Factory;

use AppBundle\Command\CreateWorkshop;
use AppBundle\Entity\Workshop;
use Doctrine\Common\Collections\ArrayCollection;

class WorkshopFactory
{
    private $idGenerator;

    /**
     * WorkshopFactory constructor.
     * @param $idGenerator
     */
    public function __construct($idGenerator)
    {
        $this->idGenerator = $idGenerator;
    }

    private static function getSlug(array $params)
    {
        return implode("-", array_map("strtolower", $params));
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
            self::getSlug([
                $command->startDate->format("Y-m-d"),
                $command->city,
                $command->title,
            ])
        );
    }
}