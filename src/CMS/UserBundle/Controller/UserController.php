<?php

namespace CMS\UserBundle\Controller;

use CMS\TeamBundle\Entity\Calendar;
use CMS\TeamBundle\Entity\Category;
use CMS\TeamBundle\Entity\Team;
use CMS\TeamBundle\Form\CategoryType;
use CMS\TeamBundle\Form\TeamType;
use CMS\UserBundle\Entity\User;
use CMS\UserBundle\Form\EditUserType;
use CMS\UserBundle\Form\UserType;
use Faker\Provider\zh_TW\DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null,  'You cannot visit this page.');

        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('CMSUserBundle:User')->findAll();

        return $this->render("CMSUserBundle:User:list.html.twig", ['users' => $users]);
    }

    public function createAction(Request $request) {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'You cannot visit this page.');

        $form = $this->createForm(new UserType());

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $data->setEnabled(1);
            $em->persist($data);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_list'));
        }

        return $this->render("CMSUserBundle:User:create.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param User $user
     * @ParamConverter("user", class="CMSUserBundle:User")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(User $user, Request $request) {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', $user, 'You cannot edit this item.');
        $user->setPlainPassword('1');
        $form = $this->createForm(new UserType(), $user);

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('admin_user_list'));
        }

        return $this->render("CMSUserBundle:User:edit.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param User $user
     * @ParamConverter("user", class="CMSUserBundle:User")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(User $user) {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', $user, 'You cannot delete this item.');

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_user_list'));
    }
}
