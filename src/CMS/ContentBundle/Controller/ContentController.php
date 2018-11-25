<?php

namespace CMS\ContentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use CMS\ContentBundle\Entity\ContentPosition;
use CMS\ContentBundle\Entity\ContentKey;
use CMS\ContentBundle\Entity\Content;
use CMS\ContentBundle\Entity\Categories;
use CMS\ContentBundle\Entity\MenuKey;
use CMS\ContentBundle\Form\AddTagsType;
use CMS\ContentBundle\Form\CategoriesType;
use CMS\ContentBundle\Form\ContentType;
use CMS\ContentBundle\Form\ContentKeyType;
use CMS\ContentBundle\Entity\Types;
use CMS\ContentBundle\Form\EditContentType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContentController extends Controller
{
	public function itemsAction($menuKey, $position, Request $request, $locale, $originalRequest)
    {
		$request->setLocale($locale);
		
		$em = $this->getDoctrine()->getManager();
		$content = $em->getRepository("CMSContentBundle:Content")
					->getContentItemsforMenuItemIDandPosition($menuKey->getId(), $position, $locale);
				
		$response = $this->render('CMSContentBundle:Content:contentview.html.twig',array('content' => $content, 'menuKey' => $menuKey, 'position' => $position, 'originalRequest' => $originalRequest ));
				
		return $response;	
    }
	
	public function gettypesAction($menuKey, $position, Request $request)
    {		
		$em = $this->getDoctrine()->getManager();
		$types = $em->getRepository("CMSContentBundle:Types")
					->findAll();
		
		$response = $this->render('CMSContentBundle:Content:addlist.html.twig',array('types' => $types, 'menuKey' => $menuKey, 'position' => $position));
				
		return $response;
    }
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
	
	public function addContentAction($categoryid, $typeid, $menuKeyid, $language, Request $request)
    {		
		$em = $this->getDoctrine()->getManager();
		$contentKey = new ContentKey();
		$contentKey->setCreationDate(new \DateTime());
		$contentType = $em->getRepository("CMSContentBundle:Types")->find($typeid);
		$contentKey->setContentType($contentType);
		
		$contentitem = new Content();
        $contentitem->setCreated(new \DateTime());
        $user = $this->getUser();
        $contentitem->setAuthor($user->getUsername());
        $contentitem->setContent('Type your text here');
        $contentitem->setPublished(1);
        $contentitem->setLanguage($language);

        $em->persist($contentitem);
        $em->flush();

		$category = $em->getRepository("CMSContentBundle:Categories")->find($categoryid);
		
		$contentitem->addCategory($category);
		$contentitem->setContentKey($contentKey);
		$contentitem->setContentTitle('Title');
		
		$contentKey->addContentItem($contentitem);
		
		$em->persist($contentKey);
		$em->persist($contentitem);
		$em->flush();
		
		return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteContentAction($itemid, Request $request, $menuKeyid)
    {
        $em = $this->getDoctrine()->getManager();
        $contentitem = $em->getRepository("CMSContentBundle:Content")->find($itemid);
        $language = $contentitem->getLanguage();
        $contentKey = $contentitem->getContentKey();
        $em->remove($contentitem);
        if(count($contentKey->getContentItems()) == 0) {
            $em->remove($contentKey);
        }
        $em->flush();

        return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));
    }
	
	public function slidecategoryAction($id, Request $request, $menuKey)
    {
		$em = $this->getDoctrine()->getManager();
		$category = $em->getRepository("CMSContentBundle:Categories");
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $content = $category->getContentItemsforCategoryIDandLanguage($id, $request->getLocale());
        } else {
            $content = $category->getFrontendContentItemsforCategoryIDandLanguage($id, $request->getLocale());
        }


		$response = $this->render('CMSContentBundle:Content:slides.html.twig',array('content' => $content, 'menuKey' => $menuKey));
				
		return $response;
    }
	
	public function categorylistAction($id, Request $request, $menuKey)
    {
		$em = $this->getDoctrine()->getManager();
		$category = $em->getRepository("CMSContentBundle:Categories");
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $content = $category->getContentItemsforCategoryIDandLanguage($id, $request->getLocale());
        } else {
            $content = $category->getFrontendContentItemsforCategoryIDandLanguage($id, $request->getLocale());
        }

		$response = $this->render('CMSContentBundle:Content:list.html.twig',array('content' => $content, 'menuKey' => $menuKey));
				
		return $response;
    }

    public function categoryAction($id, Request $request, $menuKey, $template)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("CMSContentBundle:Categories");
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $content = $category->getContentItemsforCategoryIDandLanguage($id, $request->getLocale());
        } else {
            $content = $category->getFrontendContentItemsforCategoryIDandLanguage($id, $request->getLocale());
        }
        $category = $em->getRepository("CMSContentBundle:Categories")->find($id);
        $response = $this->render($template,array('content' => $content, 'menuKey' => $menuKey, 'category' => $category));

        return $response;
    }

    public function categoryOffsetAction($id, Request $request, $menuKey, $template, $offset)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("CMSContentBundle:Categories");
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $content = $category->getContentItemsforCategoryIDandLanguage($id, $request->getLocale(),$offset);
        } else {
            $content = $category->getFrontendContentItemsforCategoryIDandLanguage($id, $request->getLocale(),$offset);
        }
        $category = $em->getRepository("CMSContentBundle:Categories")->find($id);
        $response = $this->render($template,array('content' => $content, 'menuKey' => $menuKey, 'category' => $category));

        return $response;
    }

    public function categoryByDateAction($id, Request $request, $menuKey, $template, $month)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("CMSContentBundle:Categories");
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $content = $category->getContentItemsforCategoryIDandLanguage($id, $request->getLocale());
        } else {
            $content = $category->getFrontendContentItemsforCategoryIDMonthandLanguage($id, $request->getLocale(),$month);
        }

        $response = $this->render($template,array('content' => $content, 'menuKey' => $menuKey));

        return $response;
    }

    public function showCategoryAction($id, Request $request, $menuKey)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("CMSContentBundle:Categories");
        if ($this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $content = $category->getContentItemsforCategoryIDandLanguage($id, $request->getLocale());
        } else {
            $content = $category->getFrontendContentItemsforCategoryIDandLanguage($id, $request->getLocale());
        }

        $response = $this->render('CMSContentBundle:Categories:list.html.twig',array('content' => $content, 'menuKey' => $menuKey));

        return $response;
    }

    public function showContentAction($id, Request $request, $menuKey, $originalRequest)
    {
        $em = $this->getDoctrine()->getManager();
        $content = $em->getRepository("CMSContentBundle:Content")->find($id);

        $response = $this->render('CMSContentBundle:Categories:viewCategoryItem.html.twig',array('item' => $content, 'menuKey' => $menuKey, 'originalRequest' => $originalRequest ));

        return $response;
    }

    public function getContentAction($id, Request $request, $menuKey, $originalRequest, $template)
    {
        $em = $this->getDoctrine()->getManager();
        $content = $em->getRepository("CMSContentBundle:Content")->findOneBy(array('contentKey' => $id, 'language' => $originalRequest->getLocale()));

        $response = $this->render($template,array('item' => $content, 'menuKey' => $menuKey, 'originalRequest' => $originalRequest ));

        return $response;
    }

    public function getContentByAliasAction($id, Request $request, $menuKey, $originalRequest, $template)
    {
        $em = $this->getDoctrine()->getManager();
        $content = $em->getRepository("CMSContentBundle:Content")->findOneBy(array('contentAlias' => $id, 'language' => $originalRequest->getLocale()));

        $response = $this->render($template,array('item' => $content, 'menuKey' => $menuKey, 'originalRequest' => $originalRequest ));

        return $response;
    }

    public function imagerowAction($id, Request $request, $menuKey)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("CMSContentBundle:Categories");
        $content = $category->getOnlyContentItemsforCategoryIDandLanguage($id, $request->getLocale());

        $response = $this->render('CMSContentBundle:Content:ImageRowElement.html.twig',array('content' => $content, 'menuKey'=>$menuKey));

        return $response;
    }

    public function documentrowAction($id, Request $request, $menuKey)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository("CMSContentBundle:Categories");
        $content = $category->getOnlyContentItemsforCategoryIDandLanguage($id, $request->getLocale());

        $response = $this->render('CMSContentBundle:Documents:DocumentRowElement.html.twig',array('content' => $content, 'menuKey' => $menuKey));

        return $response;
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function publishAction($contentid, $language, $menuKeyid, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $content = $em->getRepository("CMSContentBundle:Content")->find($contentid);
        $content->setPublished(1);
        $em->persist($content);
        $em->flush();

        return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function unpublishAction($contentid, $language, $menuKeyid, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $content = $em->getRepository("CMSContentBundle:Content")->find($contentid);
        $content->setPublished(0);
        $em->persist($content);
        $em->flush();

        return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addChildCategoryAction($language, $menuKeyid, $categoryid)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();
            $parentCategory = $em->getRepository("CMSContentBundle:Categories")->find($categoryid);

            $category = new Categories();
            $category->setCategoryname("New Category");
            $category->setParentcategory($parentCategory);

            $em->persist($category);
            $em->flush();

            return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));
        }
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
	public function addAction($type, $language, $menuKeyid, $position, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $em = $this->getDoctrine()->getManager();

            $contentType = $em->getRepository("CMSContentBundle:Types")->find($type);
            $menuKey = $em->getRepository("CMSContentBundle:MenuKey")->find($menuKeyid);
            $user = $this->getUser();

            if ($contentType->getClassification() == 1 or $contentType->getClassification() == 3) {
                $contentKey = new ContentKey();
                $contentKey->setCreationDate(new \DateTime());
                $contentKey->setContentType($contentType);

                $em->persist($contentKey);
                $em->flush();

                $contentitem = new Content();
                $em->persist($contentitem);
                $em->flush();
                if ($contentType->getClassification() == 3) {
                    $category = new Categories();
                    $category->setCategoryname($contentType->getTypeName());
                    $em->persist($category);
                    $em->flush();
                    $contentitem->setContent($category->getId());
                    $contentitem->setContentTitle($contentType->getTypeName() . ' ' . $category->getId());
                    $contentitem->setPublished(1);
                } else {
                    $contentitem->setContentTitle('Title');
                    $contentitem->setContent('Type your text here.');
                    $contentitem->setContentlink($menuKey->getId());
                    $contentitem->setPublished(1);
                }
                $contentitem->setLanguage($language);
                $contentitem->setContentKey($contentKey);
                $contentitem->setCreated(new \DateTime());
                $contentitem->setAuthor($user->getUsername());

                $em->persist($contentitem);
                $em->flush();

            } else if ($contentType->getClassification() == 2) {
                $contentKey = $em->getRepository("CMSContentBundle:ContentKey")->findOneBy(array('contentType' => $contentType));
            }

            $content = $em->getRepository("CMSContentBundle:Content")
                ->getContentItemsforMenuItemIDandPosition($menuKeyid, $position, $language);

            $contentposition = new ContentPosition();
            $contentposition->setPosition($position);
            $contentposition->setMenuKey($menuKey);
            $contentposition->setContentKey($contentKey);
            $contentposition->setOrdering(count($content) + 1);

            $em->persist($contentposition);
            $em->flush();
        }
		return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($contentid, $language, Request $request, $menuKeyid)
    {
        $menuKey = $this->getDoctrine()->getRepository('CMSContentBundle:MenuKey')->find($menuKeyid);

        if(!$menuKey){
            $menuKey = $this->getDoctrine()->getRepository('CMSContentBundle:MenuKey')->findOneBy(array('defaultKey' => 1));
        }

        $menuitem = $this->getDoctrine()
            ->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('menuKey' => $menuKey, 'language' => $language));

        $contentitem = $this->getDoctrine()->getRepository('CMSContentBundle:Content')->find($contentid);

        $form = $this->createForm(new EditContentType(), $contentitem, array('action' => $this->generateUrl('edit_content', array('contentid' => $contentid, 'language' => $language, 'menuKeyid'=>$menuKeyid))));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));

            $em = $this->getDoctrine()->getManager();

            $em->persist($contentitem);
            $em->flush();
        } else {
            $response = $this->render('CMSContentBundle:Content:editform.html.twig', array('menuKey' => $menuKey, 'locale' => $language, 'menuitem' => $menuitem, 'contentitem' => $contentitem, 'form' => $form->createView()));
        }

        return $response;
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editcategoryAction($categoryid, $language, Request $request, $menuKeyid)
    {
        $category = $this->getDoctrine()->getRepository('CMSContentBundle:Categories')->find($categoryid);
        $contentItems = $category->getContentItems();
        $form = $this->createForm(new CategoriesType(), $category, array('action' => $this->generateUrl('edit_category', array('categoryid' => $categoryid, 'language' => $language, 'menuKeyid'=>$menuKeyid))));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));;

            $em = $this->getDoctrine()->getManager();

            $em->persist($category);
            $em->flush();
        } else {
            $response = $this->render('CMSContentBundle:Categories:editform.html.twig', array('locale' => $language, 'form' => $form->createView(), 'contentItems'=>$contentItems));
        }

        return $response;
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editTagsAction(Request $request, $contentKey, $language, $menuKeyid)
    {
        $contentKey = $this->getDoctrine()->getRepository('CMSContentBundle:ContentKey')->find($contentKey);
        $tags = $contentKey->getTags();
        $form = $this->createForm(new AddTagsType(), $contentKey, array('action' => $this->generateUrl('add_tags', array('contentKey' => $contentKey->getId(), 'language' => $language, 'menuKeyid'=>$menuKeyid))));

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $response = $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));;

            $em = $this->getDoctrine()->getManager();

            $em->persist($contentKey);
            $em->flush();
        } else {
            $response = $this->render('CMSContentBundle:Tags:editform.html.twig', array('locale' => $language, 'form' => $form->createView(), 'tags'=>$tags));
        }

        return $response;
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
	public function deleteAction($id, $language, $position, Request $request, $menuKeyid)
    {
		$em = $this->getDoctrine()->getManager();
		
		$content = $em->getRepository("CMSContentBundle:Content")->find($id);
		$contentKey = $content->getContentKey();
		$contentpositions = $contentKey->getPositions();
		$contentItems = $contentKey->getContentItems();
        $categoryid = $content->getContent();
        $typeid = $contentKey->getContentType()->getId();
		
		$menuKey = $em->getRepository("CMSContentBundle:MenuKey")->find($menuKeyid);
		
		$contentposition = $em->getRepository("CMSContentBundle:ContentPosition")->findOneBy(array('menuKey' => $menuKey, 'contentKey' => $contentKey, 'position' => $position));
		
		if(count($contentItems) == 1 and count($contentpositions) == 1){
			$em->remove($contentposition);
			$em->remove($content);
			$em->remove($contentKey);
			$em->flush();
		} else {
			$em->remove($contentposition);
			$em->flush();
		}
		
		$contentpositions = $em->getRepository("CMSContentBundle:ContentPosition")->findBy(array('menuKey' => $menuKey, 'position' => $position), array('ordering' => 'ASC'));
		
		for($i=0; $i < count($contentpositions); $i++){
			$contentpositions[$i]->setOrdering($i+1);
			$em->persist($contentpositions[$i]);
			$em->flush();
		}

        if($typeid == 3 or $typeid == 6 or $typeid == 10){
            $category = $em->getRepository("CMSContentBundle:Categories")->find($categoryid);
                foreach($category->getContentItems() as $item){
                    $key = $item->getContentKey();
                    $em->remove($item);
                    $em->remove($key);
                    $em->flush();
                }
                $em->remove($category);
                $em->flush();
        }
		
		return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
	public function increaseAction($id, $language, $position, Request $request, $menuKeyid)
    {
		$em = $this->getDoctrine()->getManager();
		
		$content = $em->getRepository("CMSContentBundle:Content")->find($id);
		$contentKey = $content->getContentKey();
		
		$menuKey = $em->getRepository("CMSContentBundle:MenuKey")->find($menuKeyid);
		
		$contentposition = $em->getRepository("CMSContentBundle:ContentPosition")->findOneBy(array('menuKey' => $menuKey, 'contentKey' => $contentKey, 'position' => $position));
		$previousordering = $contentposition->getOrdering();
		
		if($previousordering != 1){
			$newordering = $previousordering - 1;
			$previouscontentposition = $em->getRepository("CMSContentBundle:ContentPosition")->findOneBy(array('menuKey' => $menuKey, 'position' => $position, 'ordering' => $newordering));
			
			$previouscontentposition->setOrdering($previousordering);
			$contentposition->setOrdering($newordering);
			
			$em->persist($contentposition);
			$em->persist($previouscontentposition);
			$em->flush();			
		}
		
		return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */

	public function decreaseAction($id, $language, $position, Request $request, $menuKeyid)
    {
		$em = $this->getDoctrine()->getManager();
		
		$content = $em->getRepository("CMSContentBundle:Content")->find($id);
		$contentKey = $content->getContentKey();
		
		$menuKey = $em->getRepository("CMSContentBundle:MenuKey")->find($menuKeyid);
		
		$contentposition = $em->getRepository("CMSContentBundle:ContentPosition")->findOneBy(array('menuKey' => $menuKey, 'contentKey' => $contentKey, 'position' => $position));
		$previousordering = $contentposition->getOrdering();
		
		$content = $em->getRepository("CMSContentBundle:Content")
					->getContentItemsforMenuItemIDandPosition($menuKeyid, $position, $request->getLocale());
		
		$newordering = $previousordering+1;
		
		if($previousordering < count($content)){
			$previouscontentposition = $em->getRepository("CMSContentBundle:ContentPosition")->findOneBy(array('menuKey' => $menuKey, 'position' => $position, 'ordering' => $newordering));
			
			$previouscontentposition->setOrdering($previousordering);
			$contentposition->setOrdering($newordering);
			
			$em->persist($contentposition);
			$em->persist($previouscontentposition);
			$em->flush();
		}
		
		return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language)));
		return $response;
    }

    public function loginMenuAction()
    {
        $id = 1; $_locale = 'en';

        $menuitem = $this->getDoctrine()->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('alias' => $id));
        if ($menuitem != null) {
            $menuKey = $menuitem->getMenuKey();
        }

        if ($menuitem == null) {
            $menuKey = $this->getDoctrine()
                ->getRepository('CMSContentBundle:MenuKey')
                ->find($id);
            $menuitem = $this->getDoctrine()
                ->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('menuKey' => $menuKey, 'language' => $_locale));
        }

        if ($menuitem == null) {
            $menuKey = $this->getDoctrine()->getRepository('CMSContentBundle:MenuKey')->findOneBy(array('defaultKey' => 1));
            $menuitem = $this->getDoctrine()
                ->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('menuKey' => $menuKey, 'language' => $_locale));
        }

        return $menuKey;
    }
}