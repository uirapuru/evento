<?php

namespace AppBundle\Controller;

use AppBundle\Command\CreateWorkshop;
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
                [], ["startDate" => "DESC"], 10, 0
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
            "method" => "POST"
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
     * @Route("/calendar.json", name="evento_schedule_events")
     * @param Request $request
     * @return JsonResponse
     */
    public function eventsAction(Request $request) {
        $startDate = Carbon::parse($request->get("start"));
        $endDate = Carbon::parse($request->get("end"));

        $data = $this->get("dende_calendar.occurrences_repository")->findByPeriod($startDate, $endDate);

        return new Response($this->get("jms_serializer")->serialize($data, "json"));
    }

    /**
     * @Route("/{slug}.html", name="evento_show")
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
