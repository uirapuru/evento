<?php
namespace AppBundle\Entity;

use AppBundle\DTO\SearchFilter;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @param SearchFilter $filter
     * @return array
     */
    public function findForCriteria(SearchFilter $filter)
    {
        if(!is_null($filter->search)) {
            $qb = $this->createQueryBuilder("w");
            $qb->select("w.id, w.title, w.description, w.slug");
            $expr = $qb->expr();
            $filter->search = sprintf("%%%s%%", strtolower($filter->search));
            $qb->andWhere($expr->orX(
                $expr->like("LOWER(w.title)", ':search'),
                $expr->like("LOWER(w.description)", ':search')
            ))
            ->setParameter("search", $filter->search);
            return $qb->getQuery()->getArrayResult();
        }

        return [];
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return array|Workshop[]
     */
    public function findByPeriod(DateTime $startDate, DateTime $endDate)
    {
        $qb = $this->createQueryBuilder("w");
        $expr = $qb->expr();

        $qb->join("w.lessons", "l");
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
     * @param Workshop $workshop
     */
    public function update(Workshop $workshop)
    {
        $em = $this->getEntityManager();
        $em->flush();
    }

    public function findByIds($workshopIds = [])
    {
        $qb = $this->createQueryBuilder("w");
        $expr = $qb->expr();
        $qb->select("w.id, w.title, w.description, w.slug");
        $qb->where($expr->in("w.id", $workshopIds));

        return $qb->getQuery()->getArrayResult();
    }
}