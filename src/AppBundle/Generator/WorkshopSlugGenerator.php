<?php
namespace AppBundle\Generator;

use AppBundle\Command\CreateWorkshop;
use AppBundle\Command\UpdateWorkshop;
use AppBundle\Command\WorkshopCommandInterface;
use AppBundle\Entity\Workshop;

class WorkshopSlugGenerator
{
    private $workshopRepository;

    /**
     * WorkshopSlugGenerator constructor.
     * @param $workshopRepository
     */
    public function __construct($workshopRepository)
    {
        $this->workshopRepository = $workshopRepository;
    }

    /**
     * @param Workshop|WorkshopCommandInterface $workshop
     * @return string
     */
    public function generate($workshop){
        if($workshop instanceof Workshop) {
            $params = [
                $workshop->getCity(),
                $workshop->getTitle(),
            ];
        } elseif ($workshop instanceof WorkshopCommandInterface) {
            /** @var CreateWorkshop|UpdateWorkshop $workshop */
            $params = [
                $workshop->city,
                $workshop->title,
            ];
        }

        $i = 1;

        do {
            $slug = sprintf("%s-%d", implode("-", array_map(function($el) {
                return strtolower(str_replace(" ", "-", $el));
            }, $params)), $i++);
        } while (!is_null($this->workshopRepository->findOneBySlug($slug)));

        return $slug;
    }
}