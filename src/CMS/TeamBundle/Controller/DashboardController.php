<?php

namespace CMS\TeamBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
	public function dashboardAction(Request $request)
    {
        $statisticsService = $this->get('service.statistics');
        $em = $this->getDoctrine()->getManager();
        $teamRepository = $em->getRepository('CMSTeamBundle:Team');
        $teamMemberRepository = $em->getRepository('CMSTeamBundle:TeamMember');

        $numberOfMembersPerTeamStats = $statisticsService->getFormattedDataForNameQuantityChart($teamRepository->getNumberOfMembersPerTeam());
        $numberOfNewsPerTeamStats = $statisticsService->getFormattedDataForNameQuantityChart($teamRepository->getNumberOfNewsPerTeam());

        $membersPerPaidMembershipStats = $statisticsService->getFormattedDataForNameQuantityChart($teamMemberRepository->getNumberOfTeamMembersPerPaidMembership());


        return $this->render('CMSTeamBundle:Dashboard:dashboard.html.twig', [
            'numberOfMembersPerTeamStats' => $numberOfMembersPerTeamStats,
            'numberOfNewsPerTeamStats' => $numberOfNewsPerTeamStats,
            'membersPerPaidMembershipStats' => $membersPerPaidMembershipStats
        ]);
    }
}