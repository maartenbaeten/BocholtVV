<?php

namespace CMS\TeamBundle\Controller;

use CMS\TeamBundle\Entity\Calendar;
use CMS\TeamBundle\Entity\Team;
use CMS\TeamBundle\Entity\TeamMember;
use CMS\TeamBundle\Form\TeamMemberType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamMemberController extends Controller
{
    public function indexAction()
    {
        return $this->render('CMSTeamBundle:Default:index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $members = $em->getRepository('CMSTeamBundle:TeamMember')->findAll();

        return $this->render("CMSTeamBundle:TeamMember:list.html.twig", ['members' => $members]);
    }

    /**
     * @param Team $team
     * @ParamConverter("team", class="CMSTeamBundle:Team")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listByTeamAction(Team $team) {
        $em = $this->getDoctrine()->getManager();
        $members = $em->getRepository('CMSTeamBundle:TeamMember')->findBy(['team' => $team]);

        return $this->render("CMSTeamBundle:TeamMember:list.html.twig", ['members' => $members]);
    }


    /**
     * @param TeamMember $teamMember
     * @ParamConverter("teamMember", class="CMSTeamBundle:TeamMember")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(TeamMember $teamMember) {

        return $this->render("CMSTeamBundle:TeamMember:show.html.twig", ['member' => $teamMember]);
    }

    public function createAction(Request $request) {
        $form = $this->createForm(new TeamMemberType());

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $data->setCreated(new \DateTime());
            $data->generatePlayerAlias();
            $em->persist($data);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_teammember_list'));
        }

        return $this->render("CMSTeamBundle:TeamMember:create.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param TeamMember $teamMember
     * @ParamConverter("teamMember", class="CMSTeamBundle:TeamMember")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(TeamMember $teamMember, Request $request) {
        $form = $this->createForm(new TeamMemberType(), $teamMember);

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $data->generatePlayerAlias();
            $em->flush();

            return $this->redirect($this->generateUrl('admin_teammember_list'));
        }

        return $this->render("CMSTeamBundle:TeamMember:update.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param TeamMember $teamMember
     * @ParamConverter("teamMember", class="CMSTeamBundle:TeamMember")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteImageAction(TeamMember $teamMember, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $teamMember->removeUpload();
        $teamMember->setPlayerImage(null);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_teammember_list'));
    }

    /**
     * @param TeamMember $teamMember
     * @ParamConverter("teamMember", class="CMSTeamBundle:TeamMember")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(TeamMember $teamMember) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($teamMember);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_teammember_list'));
    }

    public function importAction(Request $request) {
        if($request->getMethod() == 'POST') {
            $file = $request->files->get('team');
            if($file) {
                $em = $this->getDoctrine()->getManager();
                $objPHPExcel = \PHPExcel_IOFactory::load($file);
                $worksheet = $objPHPExcel->getActiveSheet();
                $rowIterator = $worksheet->getRowIterator();

                $team = $em->getRepository('CMSTeamBundle:Team')->find(1);

                foreach ($rowIterator as $row) {
                    $calendar = new Calendar();

                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(true);

                    $i = 1;
                    $timeString = 0;
                    foreach ($cellIterator as $cell) {
                        $calendar->setCreated(new \DateTime());
                        $calendar->setCreator('Maarten');
                        // 1 - first name; 2 - last name, 3 - team name
                        if($i == 6) {
                            $timeString = $cell->getCalculatedValue();
                        //    echo $timeString;die;
                            $date = new \DateTime();
                            $date->setTimestamp($timeString);
                            $calendar->setDate($date);
                        }
                        elseif($i == 7 and $cell->getCalculatedValue() != "KBVV") {
                            $calendar->setHome(false);
                            $calendar->setChallengerName($cell->getCalculatedValue());
                        }
                        elseif($i == 8 and $cell->getCalculatedValue() != "KBVV") {
                            $calendar->setHome(true);
                            $calendar->setChallengerName($cell->getCalculatedValue());
                        }
                        echo $i.' '.$cell->getCalculatedValue().'<br/>';
                        $i++;
                    }
                    $calendar->setTeam($team);
                    $em->persist($calendar);
                }
                $em->flush();
            }
        }

        return $this->redirect($this->generateUrl('admin_teammember_list'));
    }
}
