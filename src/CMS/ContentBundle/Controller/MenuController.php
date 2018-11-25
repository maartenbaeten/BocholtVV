<?php

namespace CMS\ContentBundle\Controller;

use CMS\ContentBundle\Entity\Menus;
use CMS\ContentBundle\Entity\MenuKey;
use CMS\ContentBundle\Entity\MenuItems;
use CMS\ContentBundle\Form\MenuItemsType;
use CMS\ContentBundle\Form\MenuKeyType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MenuController extends Controller
{
    public function indexAction($id, Request $request)
    {
		$menu = $this->getDoctrine()
			->getRepository('CMSContentBundle:Menus')
			->find($id);
			
		if (!$menu) {
			throw $this->createNotFoundException(
				'No content found for id '.$id
			);
		}
		
		$response = new Response();
		$response->setContent(json_encode(array(
			'id' => $menu->getId(),
			'title' => $menu->getTitle(),
			'description' => $menu->getDescription(),
		)));
		$response->headers->set('Content-Type', 'application/json');
		return $response;		
    }
	
	public function parentItemsAction($id, $menuKey, Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository("CMSContentBundle:MenuKey")
					->getMenu($id, $request->getLocale());
				
		return $this->render('CMSContentBundle:Menus:menu.html.twig',array('items' => $items, 'menuKey' => $menuKey));
    }

    public function onlyParentsAction($id, $menuKey, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $items = $em->getRepository("CMSContentBundle:MenuKey")
            ->getMenu($id, $request->getLocale());

        return $this->render('CMSContentBundle:Menus:parentMenu.html.twig',array('items' => $items, 'menuKey' => $menuKey));
    }
	
	public function childrenAction($id, $menuKey, Request $request, $deleterow)
    {
		$parent = $menuKey->getParentItem();
		if($parent == null){
			$keyid = $menuKey->getId();
		} else {
			$keyid = $parent->getId();
		}
		$em = $this->getDoctrine()->getManager();
		$items = $em->getRepository("CMSContentBundle:MenuKey")
					->getChildrenMenuKeys($id, $keyid, $request->getLocale());
				
		return $this->render('CMSContentBundle:Menus:childlist.html.twig',array('items' => $items, 'menuKey' => $menuKey, 'deleterow' => $deleterow));
    }

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function addAction(Request $request)
    {
		$menuKey = new MenuKey();
		$menuitem = new MenuItems();
		$menuitem->setLanguage("nl");
		$menuitemfr = new MenuItems();
		$menuitemfr->setLanguage("fr");
		$menuitemen = new MenuItems();
		$menuitemen->setLanguage("en");
		
		$menuKey->addMenuitem($menuitem);
		$menuKey->addMenuitem($menuitemfr);
		$menuKey->addMenuitem($menuitemen);
		
		$form = $this->createForm(new MenuKeyType(), $menuKey);
		
		$form->handleRequest($request);

    	if ($form->isSubmitted()) {	
			$data = $form->getData();
			$em = $this->getDoctrine()->getManager();
	
			$menuKey->setDefaultKey(0);
			$menuKey->setCreationDate(new \DateTime());
			$children = $menuKey->getParentItem()->getChildren();
			$menuKey->setOrdering(count($children)+1);
			
			if($menuitemen->getTitle() == ""){
				$menuKey->removeMenuitem($menuitemen);
			} else {
				$menuitemen->setStatus(1);
				$menuitemen->setMenuKey($menuKey);
				$em->persist($menuitemen);
				$language = 'en';
			}
			
			if($menuitemfr->getTitle() == ""){
				$menuKey->removeMenuitem($menuitemfr);
			} else {
				$menuitemfr->setStatus(1);
				$menuitemfr->setMenuKey($menuKey);
				$em->persist($menuitemfr);
				$language = "fr";
			}
			
			if($menuitem->getTitle() == ""){
				$menuKey->removeMenuitem($menuitem);
			} else {
				$menuitem->setStatus(1);
				$menuitem->setMenuKey($menuKey);
				$em->persist($menuitem);
				$language = "nl";
			}
		
			$em->persist($menuKey);
			$em->flush();
			
			return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKey->getId(), '_locale' => $language)));
			return $response;	
		}
    }
	
	public function addformAction($menuid, $parentkey, Request $request)
    {	
		$em = $this->getDoctrine()->getManager();
		$menu = $em->getRepository("CMSContentBundle:Menus")->find($menuid);
	
		$menuKey = new MenuKey();
		$menuitem = new MenuItems();
		$menuitem->setLanguage("nl");
		$menuitem->setMenu($menu);
		$menuitemfr = new MenuItems();
		$menuitemfr->setLanguage("fr");
		$menuitemfr->setMenu($menu);
		$menuitemen = new MenuItems();
		$menuitemen->setLanguage("en");
		$menuitemen->setMenu($menu);
		
		$menuKey->addMenuitem($menuitem);
		$menuKey->addMenuitem($menuitemfr);
		$menuKey->addMenuitem($menuitemen);
		
		$menuKey->setParentItem($parentkey->getParentItem());
		
		$form = $this->createForm(new MenuKeyType(), $menuKey, array('action' => $this->generateUrl('cms_content_add_menu_item'), 'attr' => array('menuid' => $menuid)));
		
		return $this->render('CMSContentBundle:Menus:addmenuItems.html.twig', array(
            'form' => $form->createView(),
        ));
	
    }

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
    public function editAction($menuKeyid, $language, Request $request){

        $menuKey = $this->getDoctrine()
            ->getRepository('CMSContentBundle:MenuKey')
            ->find($menuKeyid);

        $menuitemnl = $this->getDoctrine()
            ->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('menuKey' => $menuKey, 'language' => 'nl'));

        if(! $menuitemnl){
            $menuitemnl = new MenuItems();
            $menuitemnl->setLanguage("nl");
            $menuitemnl->setStatus(1);
            $menuKey->addMenuitem($menuitemnl);
        }

        $menuitemfr = $this->getDoctrine()
            ->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('menuKey' => $menuKey, 'language' => 'fr'));

        if(! $menuitemfr){
            $menuitemfr = new MenuItems();
            $menuitemfr->setLanguage("fr");
            $menuitemfr->setStatus(1);
            $menuKey->addMenuitem($menuitemfr);
        }

        $menuitemen = $this->getDoctrine()
            ->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('menuKey' => $menuKey, 'language' => 'en'));

        if(! $menuitemen){
            $menuitemen = new MenuItems();
            $menuitemen->setLanguage("en");
            $menuitemen->setStatus(1);
            $menuKey->addMenuitem($menuitemen);
        }
        $form = $this->createForm(new MenuKeyType(), $menuKey, array('action' => $this->generateUrl('edit_menuitem', array('menuKeyid' => $menuKeyid, 'language' => $language))));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            foreach($menuKey->getMenuItems() as $menuitem){
                if($menuitem->getTitle() == ''){
                    $menuKey->removeMenuitem($menuitem);
                    $em->remove($menuitem);
                } else {
                    $menuitem->setMenuKey($menuKey);
                    $em->persist($menuitem);
                }
            }

            $em->persist($menuKey);
            $em->flush();

            return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKey->getId(), '_locale' => $language)));
            return $response;
        }

        return $this->render('CMSContentBundle:Menus:editkey.html.twig', array('menuKey' => $menuKey, 'locale' => $language, 'menuitem' => $menuitemnl, 'form' => $form->createView()));
    }
	
	public function deleteAction($menuKeyid, $language, Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		
		$menuKey = $em->getRepository("CMSContentBundle:MenuKey")->find($menuKeyid);
		
		foreach ($menuKey->getMenuItems() as $item) {
			$em->remove($item);
		}
		
		foreach ($menuKey->getPositions() as $position) {
			if(count($position->getContentKey()->getContentItems()) == 1){
				$contentKey = $position->getContentKey();
				foreach ($contentKey->getContentItems() as $item) {
					$em->remove($item);
				}
				$em->remove($contentKey);
			}
			$em->remove($position);
		}
		
		$em->remove($menuKey);
		$em->flush();
				
		return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => 1, '_locale' => $language)));
    }

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function increaseAction($menuKeyid, $language, Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		
		$menuKey = $em->getRepository("CMSContentBundle:MenuKey")->find($menuKeyid);
		$parentkey = $menuKey->getParentItem();
				
		$children = $em->getRepository("CMSContentBundle:MenuKey")
					->getChildrenOrdering(1, $parentkey->getId(), $language);
		
		$currentordering = $menuKey->getOrdering();
		
		foreach($children as $child){
			if($child->getOrdering() < $menuKey->getOrdering()){
				$neworderid = $child->getId();
				$newordering = $child->getOrdering();
				$reorganizedchild = $child;
			}
		}
		
		$menuKey->setOrdering($newordering);
		$reorganizedchild->setOrdering($currentordering);
		
		$em->persist($menuKey);
		$em->persist($reorganizedchild);
		$em->flush();
				
		return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));
    }

	/**
	 * @Security("has_role('ROLE_ADMIN')")
	 */
	public function decreaseAction($menuKeyid, $language, Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		
		$menuKey = $em->getRepository("CMSContentBundle:MenuKey")->find($menuKeyid);
		$parentkey = $menuKey->getParentItem();
				
		$children = $em->getRepository("CMSContentBundle:MenuKey")
					->getChildrenOrdering(1, $parentkey->getId(), $language);
		
		$children = array_reverse($children);
		
		$currentordering = $menuKey->getOrdering();
		
		foreach($children as $child){
			if($child->getOrdering() > $menuKey->getOrdering()){
				$neworderid = $child->getId();
				$newordering = $child->getOrdering();
				$reorganizedchild = $child;
			}
		}
		
		$menuKey->setOrdering($newordering);
		$reorganizedchild->setOrdering($currentordering);
		
		$em->persist($menuKey);
		$em->persist($reorganizedchild);
		$em->flush();
				
		return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));
    }
}
