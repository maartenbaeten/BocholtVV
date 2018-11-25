<?php

namespace CMS\TeamBundle\EventListeners;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;


class ChangePasswordListener implements EventSubscriberInterface
{

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onChangePasswordSuccess',
        );
    }

    /**
     * ViewVacancyListener constructor.
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     */
    public function __construct(\Symfony\Component\HttpFoundation\RequestStack $requestStack, \Symfony\Component\Routing\RouterInterface $router)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->router = $router;
    }

    public function onChangePasswordSuccess(FormEvent $event) {
        $url = $this->router->generate('admin_team_dashboard');
        $response = new RedirectResponse($url);
        $event->setResponse($response);
    }
}