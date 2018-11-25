<?php

namespace CMS\TeamBundle\Controller;

use CMS\TeamBundle\Entity\Role;
use CMS\TeamBundle\Entity\Team;
use CMS\TeamBundle\Form\RoleType;
use CMS\TeamBundle\Form\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class RoleController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $roles = $em->getRepository('CMSTeamBundle:Role')->findAll();

        return $this->render("CMSTeamBundle:Role:list.html.twig", ['roles' => $roles]);
    }

    public function createAction(Request $request) {
        $form = $this->createForm(new RoleType());

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $em->persist($data);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_role_list'));
        }

        return $this->render("CMSTeamBundle:Role:create.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param Role $role
     * @ParamConverter("Role", class="CMSTeamBundle:Role")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Role $role, Request $request) {
        $form = $this->createForm(new RoleType(), $role);

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('admin_role_list'));
        }

        return $this->render("CMSTeamBundle:Role:update.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param Role $role
     * @ParamConverter("Role", class="CMSTeamBundle:Role")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Role $role) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($role);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_role_list'));
    }
}
