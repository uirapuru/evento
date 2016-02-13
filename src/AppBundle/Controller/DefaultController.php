<?php

namespace AppBundle\Controller;

use AppBundle\Command\CreateWorkshop;
use AppBundle\Command\UpdateLesson;
use AppBundle\Command\UpdateWorkshop;
use AppBundle\Controller\Helper\WorkshopsJsonWrapper;
use AppBundle\Entity\Workshop;
use Carbon\Carbon;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="evento_homepage")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        return [
            "workshops" => $this->getDoctrine()->getManager()->getRepository("AppBundle:Workshop")->findBy(
                [], [], 10, 0
            )
        ];
    }

    /**
     * @Route("/rejestracja.html", name="evento_register")
     * @Template()
     */
    public function registerAction(Request $request)
    {
        $response = new Response(null, 200);

        $form = $this->createForm("register_workshops_form_type", new CreateWorkshop(), [
            "action" => $this->generateUrl("evento_register"),
            "method" => "POST",
            "lessonTransformer" => $this->get("app.form.transform.lesson_command")
        ]);

        if($request->isMethod("POST"))
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $this->get("tactician.commandbus")->handle($form->getData());
                $this->addFlash("success", "Dziękujemy za dodanie nowej informacji, po weryfikacji przez moderatora pojawi się w bazie");
                $this->redirectToRoute("evento_homepage");
            } else {
                $response->setStatusCode(400);
            }
        }

        return $this->render("AppBundle:Default:register.html.twig", [
            "form" => $form->createView(),
        ], $response);
    }


    /**
     * @Route("/kalendarz.html", name="evento_schedule")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function calendarAction(Request $request) {
        return [];
    }

    /**
     * @Route("/events.json", name="evento_getevents")
     * @param Request $request
     * @return array
     */
    public function getEventsAction(Request $request){
        $start = Carbon::parse($request->get('start'));
        $end = Carbon::parse($request->get('end'));

        $events = $this->get("app.helper.lesson_json_wrapper")->decorate(
            $this->get("app.repository.lesson")->findByPeriod($start, $end)
        );

        $workshops = $this->get("app.helper.workshops_json_wrapper")->decorate(
            $this->get('app.repository.workshop')->findByPeriod($start, $end)
        );

        return new JsonResponse(array_merge($events, $workshops));
    }

    /**
     * @Route("/search.html", name="evento_search")
     * @Template()
     * @param Request $request
     * @return array
     */
    public function searchAction(Request $request){
        $repository = $this->get("app.repository.workshop");
        $cities = $repository->getUniqueCities();

        $form = $this->createForm("search_workshop",
            [
                "startDate" => Carbon::parse("now"),
                "endDate"   => Carbon::parse("+1 month")
            ],
            [
                "action" => $this->generateUrl("evento_search"),
                "method" => "GET",
                "cities" => array_combine($cities, $cities)
            ]
        );

        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $results = [];

            if($form->isValid())
            {
                $results = $repository->search($form->getData());
            }
        } else {
            $results = $repository->findAll([], ["startDate" => "ASC"]);
        }

        return [
            "form" => $form->createView(),
            "results" => $results
        ];
    }

    /**
     * @Route("/edit/{slug}", name="evento_edit")
     * @ParamConverter("workshop", class="AppBundle:Workshop")
     * @Template()
     * @param Request $request
     * @param Workshop $workshop
     * @return array
     */
    public function editAction(Request $request, Workshop $workshop){
        $response = new Response(null, 200);

        $form = $this->createForm("register_workshops_form_type", new UpdateWorkshop($workshop), [
            "action" => $this->generateUrl("evento_edit", ["slug" => $workshop->getSlug()]),
            "method" => "POST",
            "lessonTransformer" => $this->get("app.form.transform.lesson_command")->setCommandClass(UpdateLesson::class)
        ]);

        if($request->isMethod("POST"))
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                $this->get("tactician.commandbus")->handle($form->getData());
                $this->addFlash("success", "Edycja się udała ;)");
                $this->redirectToRoute("evento_show", ["slug" => $workshop->getSlug()]);
            } else {
                $response->setStatusCode(400);
            }
        }

        return $this->render("AppBundle:Default:edit.html.twig", [
            "form" => $form->createView(),
        ], $response);
    }

    /**
     * @Route("/show/{slug}", name="evento_show")
     * @ParamConverter("workshop", class="AppBundle:Workshop")
     * @Template()
     * @param Request $request
     * @param Workshop $workshop
     * @return array
     */
    public function showAction(Request $request, Workshop $workshop){
        return [
            "workshop" => $workshop
        ];
    }
}
