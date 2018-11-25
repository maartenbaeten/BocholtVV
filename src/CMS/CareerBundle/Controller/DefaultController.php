<?php

namespace CMS\CareerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CMSCareerBundle:Default:index.html.twig', array('name' => $name));
    }
}
