<?php
// src/EventListener/RequestListener.php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        /*
        if (!$request->hasPreviousSession()) {
            return;
        }
        */
        $host = $request->getHost();
        $locale = 'en';
        if (preg_match('/jeu-de-puzzle/', $host)) {
            $locale = 'fr';
        }
        $request->setLocale($locale);
        $request->getSession()->set('a', 'b');
    }

    public static function getSubscribedEvents() {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 16)),
        );
    }
}