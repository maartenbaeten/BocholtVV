<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function __construct($environment, $debug)
    {
        date_default_timezone_set('Europe/Amsterdam');
        parent::__construct($environment, $debug);
    }

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new CMS\UserBundle\CMSUserBundle(),
            new CMS\TemplateBundle\CMSTemplateBundle(),
            new CMS\ContentBundle\CMSContentBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle($this),
            new FOS\RestBundle\FOSRestBundle(),
            new Symfony\Cmf\Bundle\CreateBundle\CmfCreateBundle(),
			new Oneup\UploaderBundle\OneupUploaderBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new ADesigns\CalendarBundle\ADesignsCalendarBundle(),
            new Stfalcon\Bundle\TinymceBundle\StfalconTinymceBundle(),
            new CMS\CareerBundle\CMSCareerBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new CMS\TeamBundle\CMSTeamBundle(),
            new Knp\Rad\FixturesLoad\Bundle\FixturesLoadBundle(),
            new Liuggio\ExcelBundle\LiuggioExcelBundle(),
            new \Symfony\Cmf\Bundle\MediaBundle\CmfMediaBundle(),
            new FM\ElfinderBundle\FMElfinderBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
