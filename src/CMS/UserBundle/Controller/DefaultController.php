<?php

namespace CMS\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use CMS\UserBundle\Entity\User;
use CMS\CoursesBundle\Customclass\RandomGenerator;

class DefaultController extends Controller
{	
	public function loginAction(Request $request)
    {
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return array(
            'last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        );
    }

    public function resetPasswordAction(Request $request, $email){
        $user = $em->getRepository("CMSUserBundle:User")->findBy(array('email'=>$email));

        if($user){
            $mailer = $this->get('mailer');
            $message = $mailer->createMessage()
                ->setSubject('Paswoord opnieuw ingesteld')
                ->setFrom('info@owc.be')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'CMSUserBundle:Email:passwordreset.html.twig',
                        array('password' => $password, 'user' => $user)
                    ),
                    'text/html'
                );
            $mailer->send($message);
        }

    }

    public function resetPasswordFormAction(Request $request, $language){
        $menuKey = $this->getDoctrine()->getRepository('CMSContentBundle:MenuKey')->findOneBy(array('defaultKey' => 1));
        $menuitem = $this->getDoctrine()
            ->getRepository('CMSContentBundle:MenuItems')->findOneBy(array('menuKey' => $menuKey, 'language' => $language));

        if(! $this->get('request')->request->get('email')){
            $message = null;
            $response = $this->render('CMSUserBundle:User:resetpassword.html.twig', array('menuKey' => $menuKey, 'locale' => $language, 'menuitem' => $menuitem, 'message' => $message));
        } else {
            $email = $this->get('request')->request->get('email');

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("CMSUserBundle:User")->findOneBy(array('email'=>$email));

            if(!$user){
                $message['type'] = "error";
                $message["content"] = 'The given e-mail is not registered to an account!';
            } else {
                $generator = new RandomGenerator();
                $random = $generator->generateRandomString(6);
                $password = $random;
                $user->setPassword(password_hash($random, PASSWORD_BCRYPT, array('cost' => 12)));
                $em->persist($user);
                $em->flush();

                $mailer = $this->get('mailer');
                $email = $mailer->createMessage()
                    ->setSubject('Paswoord opnieuw ingesteld')
                    ->setFrom('info@owc.be')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'CMSUserBundle:Email:passwordreset.html.twig',
                            array('password' => $password, 'user' => $user)
                        ),
                        'text/html'
                    );
                $mailer->send($email);
                $massage = array();
                $message['type'] = "success";
                $message["content"] = 'Your password was reset. A new password was sent to your email!';
            }

            $response = $this->render('CMSUserBundle:User:resetpassword.html.twig', array('menuKey' => $menuKey, 'locale' => $language, 'menuitem' => $menuitem, 'message' => $message));
        }

        return $response;
    }
	
	public function securityCheckAction()
    {
        // The security layer will intercept this request
    }
	
	public function logoutAction()
    {
        // The security layer will intercept this request
    }
}
