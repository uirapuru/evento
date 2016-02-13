<?php
namespace AppBundle\Controller\Helper;

use AppBundle\Entity\Lesson;
use Symfony\Component\Routing\RouterInterface;

/**
 * @package AppBundle\Controller\Helper
 */
class LessonsJsonWrapper
{
    /** @var  RouterInterface */
    private $router;

    /**
     * WorkshopsJsonWrapper constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param array $lessons
     * @return array
     */
    public function decorate(array $lessons = [])
    {
        $lessons = array_map(function(Lesson $lesson) {
            return [
                "id" => sprintf("%s#%s", $lesson->getWorkshop()->getSlug(), $lesson->getId()),
                "start" => $lesson->getStartDate()->format("Y-m-d H:i:s"),
                "end" => $lesson->getEndDate()->format("Y-m-d H:i:s"),
                "editable" => false,
                "url" => sprintf(
                    "%s#%s",
                    $this->router->generate("evento_show", [
                        "slug" => $lesson->getWorkshop()->getSlug()
                    ]),
                    $lesson->getId()
                ),
            ];
        }, $lessons);

        return $lessons;
    }
}