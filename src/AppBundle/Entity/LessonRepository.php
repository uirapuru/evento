<?php
namespace AppBundle\Entity;

use DateTime;
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

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return array|Workshop[]
     */
    public function findByPeriod(DateTime $startDate, DateTime $endDate)
    {
        $qb = $this->createQueryBuilder("l");
        $expr = $qb->expr();

        $qb->join("l.event", "e");

        $qb->where($expr->andX(
            $expr->gte("e.startDate", ":start"),
            $expr->lte("e.startDate", ":end")
        ))
        ->setParameters([
            "start" => $startDate,
            "end" => $endDate
        ]);

        $qb->orderBy("e.startDate", "ASC");

        return $qb->getQuery()->getResult();
    }
}