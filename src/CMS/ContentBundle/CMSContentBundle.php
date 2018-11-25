<?php

namespace CMS\ContentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class CMSContentBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
