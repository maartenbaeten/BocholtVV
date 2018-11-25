<?php

namespace CMS\TeamBundle\Controller;

use CMS\TeamBundle\Entity\NewsAttachment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class NewsAttachmentController extends Controller
{
    public function downloadAction(NewsAttachment $attachment)
    {
        $file = $attachment->getAbsolutePath();
        $response = new BinaryFileResponse($file);
        $response->headers->set('Content-Disposition', 'attachment; filename="'.$attachment->getOriginalName().'";');

        return $response;
    }

    public function deleteAction(NewsAttachment $attachment) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($attachment);
            $em->flush();

        return new Response();
    }

}
