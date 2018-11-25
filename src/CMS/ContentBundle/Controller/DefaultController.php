<?php

namespace CMS\ContentBundle\Controller;

use CMS\ContentBundle\Entity\Content;
use CMS\ContentBundle\Entity\MenuItems;
use CMS\ContentBundle\Entity\MenuKey;
use Exception;
//use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM;;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($id, Request $request, $_locale, $param = null)
    {
        $menuitem = $this->getDoctrine()->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('alias' => $id));

        if($menuitem != null) {
            $menuKey = $menuitem->getMenuKey();
        }
        if($menuitem == null){

            $menuKey = $this->getDoctrine()
                ->getRepository('CMSContentBundle:MenuKey')
                ->find($id);
            /*var_dump($menuKey->getId());exit;*/
            $menuitem = $this->getDoctrine()
                ->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('id' => 1));
            //var_dump($menuitem);exit;
        }
        if($menuitem == null){
            $menuKey = $this->getDoctrine()->getRepository('CMSContentBundle:MenuKey')->findOneBy(array('defaultKey' => 1));
            $menuitem = $this->getDoctrine()
                ->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('menuKey' => $menuKey, 'language' => $_locale));
        }
        $positions = $menuKey->getPositions();
        if (!$positions) {
            throw $this->createNotFoundException(
                'No content found for id '.$id
            );
        }
        $columns = $menuitem->getColumns();
        return $this->render('CMSContentBundle:Content:'.$columns.'col.html.twig', array('menuKey' => $menuKey, 'locale' => $_locale, 'menuitem' => $menuitem));
    }

    public function popupAction($id, Request $request, $_locale)
    {
        $menuKey = $this->getDoctrine()
            ->getRepository('CMSContentBundle:MenuKey')
            ->find($id);

        $menuitem = $this->getDoctrine()
            ->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('menuKey' => $menuKey, 'language' => $_locale));

        if($menuitem == null){
            $menuKey = $this->getDoctrine()->getRepository('CMSContentBundle:MenuKey')->findOneBy(array('defaultKey' => 1));
            $menuitem = $this->getDoctrine()
                ->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('menuKey' => $menuKey, 'language' => $_locale));
        }

        $positions = $menuKey->getPositions();

        if (!$positions) {
            throw $this->createNotFoundException(
                'No content found for id '.$id
            );
        }

        return $this->render('CMSContentBundle:Content:popup.html.twig', array('menuKey' => $menuKey, 'locale' => $_locale, 'menuitem' => $menuitem));
    }
}
