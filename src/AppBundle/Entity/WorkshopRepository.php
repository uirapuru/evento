<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class WorkshopRepository extends EntityRepository
{
    /**
     * @param Workshop $workshop
     */
    public function insert(Workshop $workshop){
        $em = $this->getEntityManager();
        $em->persist($workshop);
        $em->flush();
    }
}