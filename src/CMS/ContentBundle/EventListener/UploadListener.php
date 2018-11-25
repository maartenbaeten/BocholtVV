<?php

namespace CMS\ContentBundle\EventListener;

use Oneup\UploaderBundle\Event\PostPersistEvent;

class UploadListener
{
    public function __construct($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function onUpload(PostPersistEvent $event)
    {
        $request = $event->getRequest();
        if($request->get('itemid') != null) {
            $itemid = $request->get('itemid');

            $em = $this->doctrine->getManager();
            $content = $em->getRepository("CMSContentBundle:Content")->find($itemid);
            $path = explode('web',$event->getFile()->getPathname());
            $content->setContentimage($path[1]);

            $em->persist($content);
            $em->flush();
        }
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file) {
            unlink($file);
        }
    }
}

