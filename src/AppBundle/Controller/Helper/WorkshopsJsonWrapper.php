<?php
namespace AppBundle\Controller\Helper;

use AppBundle\Entity\Workshop;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class WorkshopsJsonWrapper
 * @package AppBundle\Controller\Helper
 */
class WorkshopsJsonWrapper
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
     * @param array $workshops
     * @return array
     */
    public function decorate(array $workshops = [])
    {
        $workshops = array_map(function(Workshop $workshop) {
            return [
                "id" => $workshop->getSlug(),
                "start" => $workshop->getStartDate()->format("Y-m-d H:i:s"),
                "end" => $workshop->getEndDate()->format("Y-m-d H:i:s"),
                "rendering" => "background",
                "editable" => false,
                "backgroundColor" => '',
                "url" => $this->router->generate("evento_show", ["slug" => $workshop->getSlug()]),
            ];
        }, $workshops);

        return $workshops;
    }
}