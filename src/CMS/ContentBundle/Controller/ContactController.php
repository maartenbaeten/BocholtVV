<?php

namespace CMS\ContentBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
	public function submitAction(Request $request, $menuKeyid, $language)
    {
		$name = $request->request->get('name');
        $email = $request->request->get('email');
        $subject = $request->request->get('subject');
        $message = $request->request->get('message');

        $mailer = $this->get('mailer');
        $message = $mailer->createMessage()
            ->setSubject($subject)
            ->setFrom($email)
            ->setTo('maarten.baeten27@gmail.com')
            ->setBody(
                $this->renderView(
                    'CMSContentBundle:Email:contactemail.html.twig',
                    array('subject' => $subject, 'message' => $message, 'email' => $email)
                ),
                'text/html'
            );
        $mailer->send($message);

        return $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language, 'sent' => 1)));
    }

}