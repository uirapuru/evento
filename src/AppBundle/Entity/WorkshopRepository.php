<?php
namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\Paginator;

class WorkshopRepository extends EntityRepository
{
    /**
     * @var Paginator
     */
    private $paginator;

    /**
     * @param Paginator $paginator
     */
    public function setPaginator($paginator)
    {
        $this->paginator = $paginator;
    }

    /**
     * @param Workshop $workshop
     */
    public function insert(Workshop $workshop){
        $em = $this->getEntityManager();
        $em->persist($workshop);
        $em->flush();
    }

    /**
     * @param array $parameters
     * @param int $page
     * @param int $limit
     * @return Workshop[]|array
     */
    public function search($parameters, $page = 1, $limit = PHP_INT_MAX)
    {
        if(!is_null($parameters["search"])) {
            $query = $this->getFindByTextQuery($parameters["search"]);
        } elseif(!is_null($parameters["city"])) {
            $query = $this->getFindByCityQuery($parameters["city"]);
        } else {
            $query = $this->getFindByPeriodQuery($parameters["startDate"], $parameters["endDate"]);
        }

        return $this->paginator->paginate($query, $page, $limit);
    }

    /**
     * @param string $text
     * @return array|Workshop[]
     */
    private function getFindByTextQuery($text)
    {
        $text = sprintf("'%%%s%%'", strtolower($text));

        $qb = $this->createQueryBuilder("w");
        $expr = $qb->expr();

        $qb->where($expr->orX(
            $expr->like("LOWER(w.title)", $text),
            $expr->like("LOWER(w.description)", $text)
        ));

        $qb->orderBy("w.startDate", "ASC");

        return $qb->getQuery();
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return array|Workshop[]
     */
    public function findByPeriod(DateTime $startDate, DateTime $endDate)
    {
        $query = $this->getFindByPeriodQuery($startDate, $endDate);
        return $query->getResult();
    }

    /**
     * @param Workshop $workshop
     */
    public function update(Workshop $workshop)
    {
        $em = $this->getEntityManager();
        $em->flush();
    }

    /**
     * @param $city
     * @return \Doctrine\ORM\Query
     */
    private function getFindByCityQuery($city)
    {
        $qb = $this->createQueryBuilder("w");

        $qb->join("w.lessons", "l");

        $qb->where("LOWER(l.city) = :city")
            ->setParameters([
                "city" => $city,
            ]);

        $qb->orderBy("e.startDate", "ASC");

        return $qb->getQuery();
    }


    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return \Doctrine\ORM\Query
     */
    private function getFindByPeriodQuery(DateTime $startDate, DateTime $endDate)
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

        return $qb->getQuery();
    }

    /**
     * @param $page
     * @param $limit
     * @param $options
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    public function findAllPaginated($page = 1, $limit = PHP_INT_MAX, $options = []) {
        $qb = $this->createQueryBuilder("w");
        return $this->paginator->paginate($qb->getQuery(), $page, $limit, $options);
    }
}