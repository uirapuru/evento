<?php
namespace AppBundle\Entity;

use AppBundle\DTO\SearchFilter;
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

    /**
     * @return array|string[]
     */
    public function getUniqueCities()
    {
        $qb = $this->createQueryBuilder("l");

        $query = $qb->select("l.city")
            ->distinct(true)
            ->orderBy("l.city", "ASC")
            ->getQuery();

        $result = array_map(function ($element) {
            return $element["city"];
        }, $query->getArrayResult());

        return $result;
    }

    /**
     * @param SearchFilter $filter
     * @return array
     */
    public function findForCriteria(SearchFilter $filter)
    {
        if ($filter->isEmpty()) {
            return [];
        }

        $qb = $this->createQueryBuilder("l");
        $expr = $qb->expr();

        $qb->select("w.id AS workshop_id, l.city, e.startDate, e.endDate");

        $qb->join("l.event", "e");
        $qb->join("l.workshop", "w");

        $qb->addOrderBy("w.id");
        $qb->addOrderBy("e.startDate", "ASC");

        if (!is_null($filter->city)) {
            $filter->city = strtolower($filter->city);
            $qb->andWhere("LOWER(l.city) = :city")->setParameter("city", $filter->city);
        }

        if (!is_null($filter->search)) {
            $filter->search = sprintf("%%%s%%", strtolower($filter->search));
            $qb->andWhere($expr->orX(
                $expr->like("LOWER(l.title)", ':search'),
                $expr->like("LOWER(l.description)", ':search')
            ))
                ->setParameter("search", $filter->search);
        }

        if (!is_null($filter->startDate)) {
            $qb->andWhere($expr->gte("e.startDate", ":start"));
            $qb->setParameter("start", $filter->startDate);
        }

        if (!is_null($filter->endDate)) {
            $qb->andWhere($expr->lte("e.endDate", ":end"));
            $qb->setParameter("end", $filter->endDate);
        }

        return $qb->getQuery()->getArrayResult();
    }

    public function findByWorkshopIds($workshopIds = [])
    {
        $qb = $this->createQueryBuilder("l");
        $expr = $qb->expr();
        $qb->select("w.id AS workshop_id, l.city, e.startDate, e.endDate");

        $qb->join("l.event", "e");
        $qb->join("l.workshop", "w");

        $qb->addOrderBy("w.id");
        $qb->addOrderBy("e.startDate", "ASC");

        $qb->where($expr->in("w.id", $workshopIds));

        return $qb->getQuery()->getArrayResult();
    }
}
