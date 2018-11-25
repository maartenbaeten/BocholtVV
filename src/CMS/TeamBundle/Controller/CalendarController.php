<?php

namespace CMS\TeamBundle\Controller;

use CMS\TeamBundle\Entity\Calendar;
use CMS\TeamBundle\Entity\Team;
use CMS\TeamBundle\Form\CalendarType;
use CMS\TeamBundle\Form\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class CalendarController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $calendar = $em->getRepository('CMSTeamBundle:Calendar')->findAll();

        return $this->render("CMSTeamBundle:Calendar:list.html.twig", ['calendar' => $calendar]);
    }

    public function createAction(Request $request) {
        $form = $this->createForm(new CalendarType());

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $data->setCreated(new \DateTime());
            $em->persist($data);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_calendar_list'));
        }

        return $this->render("CMSTeamBundle:Calendar:create.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param Calendar $calendar
     * @ParamConverter("calendar", class="CMSTeamBundle:Calendar")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Calendar $calendar, Request $request) {
        $form = $this->createForm(new CalendarType(), $calendar);

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('admin_calendar_list'));
        }

        return $this->render("CMSTeamBundle:Calendar:update.html.twig", ['form' => $form->createView()]);
    }


    /**
     * @param Calendar $calendar
     * @ParamConverter("calendar", class="CMSTeamBundle:Calendar")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Calendar $calendar) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($calendar);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_calendar_list'));
    }
}
