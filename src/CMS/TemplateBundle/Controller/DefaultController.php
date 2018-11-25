<?php

namespace CMS\TemplateBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
	/**
	* @Template()
     */
    public function indexAction($menuitemid)
    {
        return $this->render('CMSTemplateBundle:Bocholtvv:index.html.twig',array('menuitemid' => $menuitemid));
    }
	
}
