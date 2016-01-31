<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LessonRepository extends EntityRepository
{
    public function insert(Lesson $lesson)
    {
        $em = $this->getEntityManager();
        $em->persist($lesson);
        $em->flush();
    }
}