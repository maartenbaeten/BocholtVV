<?php

namespace CMS\TeamBundle\Controller;

use CMS\TeamBundle\Entity\Calendar;
use CMS\TeamBundle\Entity\Category;
use CMS\TeamBundle\Entity\Team;
use CMS\TeamBundle\Form\CategoryType;
use CMS\TeamBundle\Form\TeamType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class CategoryController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('CMSTeamBundle:Category')->findAll();

        return $this->render("CMSTeamBundle:Category:list.html.twig", ['categories' => $categories]);
    }

    public function createAction(Request $request) {
        $form = $this->createForm(new CategoryType());

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $em->persist($data);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_category_list'));
        }

        return $this->render("CMSTeamBundle:Category:create.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param Category $category
     * @ParamConverter("category", class="CMSTeamBundle:Category")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Category $category, Request $request) {
        $form = $this->createForm(new CategoryType(), $category);

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl('admin_category_list'));
        }

        return $this->render("CMSTeamBundle:Category:edit.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param Category $category
     * @ParamConverter("category", class="CMSTeamBundle:Category")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteAction(Category $category) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_category_list'));
    }
}
