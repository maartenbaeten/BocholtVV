<?php

namespace CMS\TeamBundle\Controller;

use CMS\TeamBundle\Entity\Calendar;
use CMS\TeamBundle\Entity\Category;
use CMS\TeamBundle\Entity\Team;
use CMS\TeamBundle\Entity\TeamRepository;
use CMS\TeamBundle\Form\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class TeamController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $teams = $em->getRepository('CMSTeamBundle:Team')->findAll();

        return $this->render("CMSTeamBundle:Team:list.html.twig", ['teams' => $teams]);
    }

    /**
     * @param Category $category
     * @ParamConverter("category", class="CMSTeamBundle:Category")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listByCategoryAction(Category $category) {
        $em = $this->getDoctrine()->getManager();
        $teams = $em->getRepository('CMSTeamBundle:Team')->findBy(['category' => $category]);

        return $this->render("CMSTeamBundle:Team:listByCategory.html.twig", ['teams' => $teams, 'category' => $category]);
    }

    public function listAllAction($template, $originalRequest, $menuKey) {
        $em = $this->getDoctrine()->getManager();
        $teams = $em->getRepository('CMSTeamBundle:Team')->findBy([],['ordering' => 'ASC']);

        return $this->render($template, ['teams' => $teams, 'originalRequest' => $originalRequest, 'menuKey' => $menuKey]);
    }

    public function getTeamAction($alias, $template, $originalRequest){
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('CMSTeamBundle:Team')->findOneBy(['teamAlias' => $alias]);

        return $this->render($template, ['team' => $team, 'originalRequest' => $originalRequest]);
    }

    public function getlatestGameAction($template, $originalRequest, $teamId) {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository('CMSTeamBundle:Team')->findLatestGame($teamId);
        if(isset($game[0])){
            $game = $game[0];
        } else {
            $game = null;
        }

        return $this->render($template, ['game' => $game, 'originalRequest' => $originalRequest]);
    }

    /**
     * @param Team $team
     * @ParamConverter("team", class="CMSTeamBundle:Team", options={"id" = "teamId"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function teamCalendarAction(Team $team, $template){
        $em = $this->getDoctrine()->getManager();
        $elements = $em->getRepository('CMSTeamBundle:Calendar')->findBy(['team' => $team], ['date' => 'ASC']);

        return $this->render($template, ['elements' => $elements]);
    }

    /**
     * @param Team $team
     * @ParamConverter("team", class="CMSTeamBundle:Team", options={"id" = "teamId"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function youthCalendarAction(Team $team){
        $tableResult = $this->readTeam($team);
        $tableResult = str_replace('Uitslagen en kalender','',$tableResult);

        return $this->render("CMSTeamBundle:Frontend:html-result.html.twig", ['result' => $tableResult[3]]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function youthAppointmentsAction(){
        $tableResult = $this->readAppointments();

        return $this->render("CMSTeamBundle:Frontend:html-result-foreach.html.twig", ['result' => $tableResult]);
    }

    /**
     * @param Team $team
     * @param Boolean $staff
     * @ParamConverter("team", class="CMSTeamBundle:Team", options={"id" = "teamId"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listFrontendAction(Team $team, $originalRequest, $staff) {
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository('CMSTeamBundle:Role')->findBy(['staff' => $staff], ['number' => 'ASC']);

        $teamSkeleton = [];

        foreach($roles as $role){
            $roleMembers = $em->getRepository('CMSTeamBundle:TeamMember')->findBy(['role' => $role, 'team' => $team]);
            $teamSkeleton[$role->getRoleName()] = $roleMembers;
        }

        return $this->render("CMSTeamBundle:Frontend:team-member.html.twig", ['roles' => $roles, 'team' => $team, 'teamSkeleton' => $teamSkeleton, 'originalRequest' => $originalRequest]);
    }

    /**
     * @param Team $team
     * @param Boolean $staff
     * @ParamConverter("team", class="CMSTeamBundle:Team", options={"id" = "teamId"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllFrontendAction(Team $team, $staff = 0) {
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository('CMSTeamBundle:Role')->findBy(['staff' => $staff],['number' => 'ASC']);

        $teamMembers = $em->getRepository('CMSTeamBundle:TeamMember')->findBy(['role' => $roles, 'team' => $team]);

        return $this->render("CMSTeamBundle:Frontend:team-member-youth.html.twig", ['roles' => $roles, 'team' => $team, 'teamMembers' => $teamMembers ]);
    }

    /**
     * @param Team $team
     * @param Boolean $staff
     * @ParamConverter("team", class="CMSTeamBundle:Team", options={"id" = "teamId"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAllFrontendYouthAction(Team $team, $staff = 0) {
        $em = $this->getDoctrine()->getManager();

        $roles = $em->getRepository('CMSTeamBundle:Role')->findBy(['staff' => $staff],['number' => 'ASC']);
        $teamMembers = $em->getRepository('CMSTeamBundle:TeamMember')->findBy(['team' => $team, 'role' => $roles]);
        if($staff){
            return $this->render("CMSTeamBundle:Frontend:staff-member-youth.html.twig", ['team' => $team, 'teamMembers' => $teamMembers]);
        } else {
            return $this->render("CMSTeamBundle:Frontend:team-member-youth.html.twig", ['team' => $team, 'teamMembers' => $teamMembers]);
        }
    }

    public function viewAction($alias){
        $em = $this->getDoctrine()->getManager();
        $member = $em->getRepository('CMSTeamBundle:TeamMember')->findOneBy(['playerAlias' => $alias]);

        return $this->render("CMSTeamBundle:Frontend:team-member-detail.html.twig", ['member' => $member]);
    }

    /**
     * @param Team $team
     * @ParamConverter("team", class="CMSTeamBundle:Team", options={"id" = "teamId"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function classificationAction(Team $team){
        $classification = $team->getClassification();

        return $this->render("CMSTeamBundle:Frontend:classification-elements.html.twig", ['classification' => $classification]);
    }

    /**
     * @param Team $team
     * @ParamConverter("team", class="CMSTeamBundle:Team", options={"id" = "teamId"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function youthClassificationAction(Team $team){

        $tableResult = $this->readTeam($team);
        $tableResult = str_replace('Algemene rangschikking','',$tableResult);
        $tableResult = str_replace('Doelpunten','',$tableResult);

        return $this->render("CMSTeamBundle:Frontend:html-result.html.twig", ['result' => $tableResult[5]]);
    }

    function readTeam(Team $team){
        if($team->getTeamCode()){
            $teamCode = $team->getTeamCode();
        } else {
            $teamCode = 'PCC67552';
        }
        $postdata = http_build_query(
            array(
                'matricule' => '00595',
                'wat' => 'data',
                'command' => 'bekijken',
                'selectedSerPlus_id' => $teamCode,
                'KBVB_datumvan_dag' => '06',
                'KBVB_datumvan_maand' => '07',
                'KBVB_datumvan_jaar' => '2016',
                'KBVB_datumtot_dag' => '20',
                'KBVB_datumtot_maand' => '07',
                'KBVB_datumtot_jaar' => '2017',
                'enkel' => 'test',
                'LANG' => 'N',
                'secid' => '1165',
                'useCssNewFootbel' => 'hosted-pages'
            )
        );

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        $context  = stream_context_create($opts);

        $result = file_get_contents('http://assist.e-kickoff.com/kbvb_publiek/kalender.do', false, $context);

        $tags = array("<center>", "</center>");
        $result = str_replace($tags, "", $result);

        $doc = new \DOMDocument();

        $doc->loadHTML($result);
        $tables = $doc->getElementsByTagName('table');

        $x = new \DOMXPath($doc);
        $tableResult = [];
        foreach($x->query('//table') as $table){
            $tableResult[] = $table->C14N();
        }

        return $tableResult;
    }

    function readAppointments(){
        $result = file_get_contents('http://static.belgianfootball.be/project/publiek/aanduidingenclub/nl/aanduiding_00595.htm');
        $result = str_replace(['FFFFFF'],'4',$result);
        $result = str_replace(['bgcolor'],'colspan',$result);
        $result = strip_tags($result, '<table><tr><td><tbody><b>');

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($result);

        $x = new \DOMXPath($doc);
        $tableResult = [];
        foreach($x->query('//table[@class=\'table1\']') as $table){
            $tableResult[] = $table->C14N();
        }

        return $tableResult;
    }

    function DOMinnerHTML(DOMNode $element)
    {
        $innerHTML = "";
        $children  = $element->childNodes;

        foreach ($children as $child)
        {
            $innerHTML .= $element->ownerDocument->saveHTML($child);
        }

        return $innerHTML;
    }

    /**
     * @param Team $team
     * @ParamConverter("team", class="CMSTeamBundle:Team")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Team $team) {

        return $this->render("CMSTeamBundle:Team:show.html.twig", ['team' => $team]);
    }

    public function createAction(Request $request) {
        $form = $this->createForm(new TeamType());

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $data->setCreated(new \DateTime());
            $data->generateTeamAlias();
            $em->persist($data);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_team_list'));
        }

        return $this->render("CMSTeamBundle:Team:create.html.twig", ['form' => $form->createView()]);
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
                    $timeString = "";
                    foreach ($cellIterator as $cell) {
                        // 1 - first name; 2 - last name, 3 - team name
                        if($i == 3) {
                            $timeString = strtotime($cell->getCalculatedValue());
                        }
                        elseif($i == 4) {
                            $timeString = $timeString.' '.$cell->getCalculatedValue();
                            $calendar->setDate($timeString);
                        }
                        elseif($i == 5 and $cell->getCalculatedValue() != "KBVV") {
                            $calendar->setHome(false);
                            $calendar->setChallengerName($cell->getCalculatedValue());
                        }
                        elseif($i == 6 and $cell->getCalculatedValue() != "KBVV") {
                            $calendar->setHome(true);
                            $calendar->setChallengerName($cell->getCalculatedValue());
                        }
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

    /**
     * @param Team $team
     * @ParamConverter("Team", class="CMSTeamBundle:Team")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Team $team, Request $request) {
        $form = $this->createForm(new TeamType(), $team);

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $data->generateTeamAlias();
            $em->flush();

            return $this->redirect($this->generateUrl('admin_team_list'));
        }

        return $this->render("CMSTeamBundle:Team:update.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param Team $team
     * @param Request $request
     * @ParamConverter("Team", class="CMSTeamBundle:Team")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteImageAction(Team $team, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $team->removeUpload();
        $team->setTeamImage(null);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_team_list'));
    }

    public function latestResultAction(){

    }

    /**
     * @param Team $team
     * @ParamConverter("Team", class="CMSTeamBundle:Team")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Team $team) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($team);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_team_list'));
    }
}
