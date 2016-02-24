<?php
namespace AppBundle\DataProvider;

use AppBundle\DTO\SearchFilter;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\LessonRepository;
use AppBundle\Entity\Workshop;
use AppBundle\Entity\WorkshopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class SearchResults
{
    /** @var  WorkshopRepository */
    private $workshopRepository;

    /** @var  LessonRepository */
    private $lessonsRepository;

    /** @var  PaginatorInterface */
    private $paginator;

    /** @var array|Workshop[] */
    public $results = [];

    /** @var PaginationInterface|SlidingPagination */
    public $pagination = null;

    /**
     * SearchResults constructor.
     * @param WorkshopRepository $workshopRepository
     * @param LessonRepository $lessonsRepository
     */
    public function __construct(WorkshopRepository $workshopRepository, LessonRepository $lessonsRepository, PaginatorInterface $paginator)
    {
        $this->workshopRepository = $workshopRepository;
        $this->lessonsRepository = $lessonsRepository;
        $this->paginator = $paginator;
    }

    public function search(SearchFilter $filter, $page = 1, $limit = 10)
    {
        // get workshop and lessons filtered separately
        $workshops = $this->workshopRepository->findForCriteria($filter);
        $lessons = $this->lessonsRepository->findForCriteria($filter);

        // get all lessons for found workshops
        if (count($workshops) > 0) {
            $lessonsFromWorkshops = $this->lessonsRepository->findByWorkshopIds(
                array_unique(array_column($workshops, "id"))
            );
            $lessons = array_unique(array_merge($lessons, $lessonsFromWorkshops), SORT_REGULAR);
        }

        // get all workshops for found lessons
        if (count($lessons) > 0) {
            $workshopsFromLessons = $this->workshopRepository->findByIds(
                array_unique(array_column($lessons, "workshop_id"))
            );
            $workshops = array_unique(array_merge($workshops, $workshopsFromLessons), SORT_REGULAR);
        }

        // join of the lessons and workshops
        $result = array_map(function($workshopArray) use ($lessons){
            $workshopArray["lessons"] = array_values(array_filter($lessons, function($lessonArray) use ($workshopArray){
                return $lessonArray["workshop_id"] == $workshopArray["id"];
            }));

            return $workshopArray;
        }, $workshops);

        $this->pagination = $this->paginator->paginate($result, $page, $limit);
        $this->results = $result;

        return $this;
    }
}