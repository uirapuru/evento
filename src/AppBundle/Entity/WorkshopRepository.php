<?php
namespace AppBundle\Entity;

use DateTime;
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
     * @return array|string[]
     */
    public function getUniqueCities()
    {
        $qb = $this->createQueryBuilder("w");

        $query = $qb->select("w.city")
            ->distinct(true)
            ->orderBy("w.city", "ASC")
            ->getQuery();

        $result = array_map(function($element) {
            return $element["city"];
        }, $query->getArrayResult());

        return $result;
    }

    /**
     * @param array $parameters
     * @return Workshop[]|array
     */
    public function search($parameters)
    {
        if(!is_null($parameters["search"])) {
            return $this->findByText($parameters["search"]);
        } elseif(!is_null($parameters["city"])) {
            return $this->findByCity($parameters["city"]);
        } else {
            return $this->findByPeriod($parameters["startDate"], $parameters["endDate"]);
        }
    }

    /**
     * @param string $text
     * @return array|Workshop[]
     */
    private function findByText($text)
    {
        $text = sprintf("'%%%s%%'", strtolower($text));

        $qb = $this->createQueryBuilder("w");
        $expr = $qb->expr();

        $qb->where($expr->orX(
            $expr->like("LOWER(w.title)", $text),
            $expr->like("LOWER(w.description)", $text)
        ));

        $qb->orderBy("w.startDate", "ASC");

        return $qb->getQuery()->getResult();
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

        $qb->where($expr->andX(
            $expr->gte("w.startDate", ":start"),
            $expr->lte("w.startDate", ":end")
        ))
        ->setParameters([
            "start" => $startDate,
            "end" => $endDate
        ]);

        $qb->orderBy("w.startDate", "ASC");

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
}