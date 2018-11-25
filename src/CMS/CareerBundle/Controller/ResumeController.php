<?php

namespace CMS\CareerBundle\Controller;

use CMS\CareerBundle\Entity\Resume;
use CMS\CareerBundle\Form\ResumeType;
use CMS\CareerBundle\Form\ResumeWideType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ResumeController extends Controller
{
    public function formAction(Request $request, $language)
    {
        $resume = new Resume();
        $form = $this->createForm(new ResumeType(), $resume);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $dreamjob = $resume->getDreamjob();
            $stringjob = '';
            foreach($dreamjob as $job){
                $stringjob = $stringjob.' '.$job;
            }
            $resume->setDreamjob($stringjob);
            $resume->upload();
            $resume->setAdded(new \DateTime());
            $em->persist($resume);
            $em->flush();

            $this->notifyCustomer($resume, $language);
            $this->notifyCompany($resume);

            $response = $this->render('CMSCareerBundle:Uploader:uploadsuccess.html.twig');
        } else {
            $response = $this->render('CMSCareerBundle:Uploader:uploadform.html.twig',array('form' => $form->createView()));
        }

        return $response;
    }

    public function wideformAction(Request $request, $language, $subject, $menuKeyid, $contentid)
    {
        $resume = new Resume();
        $resume->setDreamjob(trim($subject));
        $form = $this->createForm(new ResumeWideType(), $resume, array('action' => $this->generateUrl('uploadform_wide', array('language' => $language, 'menuKeyid' => $menuKeyid, 'contentid' => $contentid))));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $resume->upload();
            $resume->setAdded(new \DateTime());
            $em->persist($resume);
            $em->flush();

            $this->notifyCustomer($resume, $language);
            $this->notifyCompany($resume);

            $response = $response = $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language, 'contentid' => $contentid, 'uploaded' => 1)));
        } else {
            $response = $this->render('CMSCareerBundle:Uploader:uploadformwide.html.twig',array('form' => $form->createView()));
        }

        return $response;
    }

    public function submitwideformAction(Request $request, $language, $menuKeyid, $contentid)
    {
        $resume = new Resume();
        $form = $this->createForm(new ResumeWideType(), $resume);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $resume->upload();
            $resume->setAdded(new \DateTime());
            $em->persist($resume);
            $em->flush();

            $this->notifyCustomer($resume, $language);
            $this->notifyCompany($resume);

            $response = $this->redirect($this->generateUrl('cms_content_homepage', array('id' => $menuKeyid, '_locale' => $language, 'contentid' => $contentid, 'uploaded' => 1)));
        } else {
            $response = $this->wideformAction($request,$language,$resume->getDreamjob(),$menuKeyid, $contentid);
        }

        return $response;
    }

    private function notifyCustomer($resume, $language)
    {
        if($language == 'nl') {
            $template = 'CMSTemplateBundle:Crossbridge:Customeremail.html.twig';
        } else {
            $template = 'CMSTemplateBundle:Crossbridge:Customeremail'.$language.'.html.twig';
        }
        $message = \Swift_Message::newInstance()
            ->setSubject('CV Upload Succesvol')
            ->setFrom('info@crossbridge.eu')
            ->setTo($resume->getEmail())
            ->setBody(
                $this->renderView(
                    $template
                ),
                'text/html'
            )
        ;
        $this->get('mailer')->send($message);
    }

    private function notifyCompany($resume)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('CV Upload')
            ->setFrom($resume->getEmail())
            ->setTo('maarten.baeten27@gmail.com')
            ->setBody(
                $this->renderView(
                    'CMSTemplateBundle:Crossbridge:Crossbridgeemail.html.twig',
                    array('resume' => $resume)
                ),
                'text/html'
            )
        ;
        $this->get('mailer')->send($message);
    }
}
