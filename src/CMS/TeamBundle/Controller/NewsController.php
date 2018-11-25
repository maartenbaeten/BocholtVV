<?php

namespace CMS\TeamBundle\Controller;

use CMS\TeamBundle\Entity\News;
use CMS\TeamBundle\Entity\Team;
use CMS\TeamBundle\Form\NewsType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('CMSTeamBundle:News')->findAll();

        return $this->render("CMSTeamBundle:News:list.html.twig", ['news' => $news]);
    }

    /**
     * @param Team $team
     * @ParamConverter("Team", class="CMSTeamBundle:Team")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listByTeamAction(Team $team) {
        $em = $this->getDoctrine()->getManager();
        $news = $em->getRepository('CMSTeamBundle:News')->findBy(['team' => $team]);

        return $this->render("CMSTeamBundle:News:list.html.twig", ['news' => $news]);
    }

    /**
     * @param integer $id
     * @param string $template
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listByTeamFrontendAction($id, $template, $originalRequest) {
        $em = $this->getDoctrine()->getManager();
        $team = $em->getRepository('CMSTeamBundle:Team')->find($id);
        $newsItems = $em->getRepository('CMSTeamBundle:News')->findBy(['team' => $team]);

        return $this->render($template, ['newsItems' => $newsItems, 'originalRequest' => $originalRequest]);
    }

    /**
     * @param News $item
     * @ParamConverter("item", class="CMSTeamBundle:News")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(News $item) {

        return $this->render("CMSTeamBundle:News:show.html.twig", ['item' => $item]);
    }

    public function createAction(Request $request) {
        $form = $this->createForm(new NewsType());

        $form->handleRequest($request);
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $data = $form->getData();
            $data->setCreated(new \DateTime());
            $em->persist($data);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_news_list'));
        }

        return $this->render("CMSTeamBundle:News:create.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param News $item
     * @ParamConverter("item", class="CMSTeamBundle:News")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(News $item, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(new NewsType(), $item);

        $originalAttachments = new ArrayCollection();

        // Create an ArrayCollection of the current Tag objects in the database
        foreach ($item->getAttachments() as $attachment) {
            $originalAttachments->add($attachment);
        }

        $form->handleRequest($request);
        if($form->isValid()) {
            // remove the relationship between the tag and the Task
            foreach ($originalAttachments as $attachment) {
                if ($item->getAttachments()->contains($attachment) === false) {
                    $em->remove($attachment);
                }
            }

            $em->flush();

            return $this->redirect($this->generateUrl('admin_news_list'));
        }

        return $this->render("CMSTeamBundle:News:update.html.twig", ['form' => $form->createView()]);
    }

    /**
     * @param News $item
     * @param Request $request
     * @ParamConverter("item", class="CMSTeamBundle:News")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteImageAction(News $item, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $item->removeUpload();
        $item->setNewsImage(null);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_news_list'));
    }

    /**
     * @param News $item
     * @ParamConverter("item", class="CMSTeamBundle:News")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(News $item) {
        $em = $this->getDoctrine()->getManager();
        $em->remove($item);
        $em->flush();

        return $this->redirect($this->generateUrl('admin_news_list'));
    }
}
