<?php
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class LessonRepository extends EntityRepository
{
    /**
     * @param Lesson $lesson
     */
    public function insert(Lesson $lesson)
    {
        $em = $this->getEntityManager();
        $em->persist($lesson);
        $em->flush();
    }

    /**
     * @param Lesson $existingLesson
     */
    public function remove(Lesson $existingLesson)
    {
        $em = $this->getEntityManager();
        $em->remove($existingLesson);
        $em->flush();
    }
}