<?php

/**
 * This file is part of Contao.
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @license LGPL-3.0+
 */

namespace Contao\CoreBundle\EventListener;

use Contao\System;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 * Sets the TL_VIEW cookie based on the "toggle_view" query parameter.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 * @author Andreas Schempp <https://github.com/aschempp>
 */
class ToggleViewListener
{
    /**
     * Toggles the TL_VIEW cookie and redirects back to the referring page.
     *
     * @param GetResponseEvent $event The event object
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (!$request->query->has('toggle_view')) {
            return;
        }

        $state    = $request->query->get('toggle_view');
        $referer  = System::getReferer();
        $response = new RedirectResponse($referer, 303);

        if ('mobile' === $state) {
            $cookie = new Cookie('TL_VIEW', 'mobile', 0, $request->getBasePath());
        } else {
            $cookie = new Cookie('TL_VIEW', 'desktop', 0, $request->getBasePath());
        }

        $response->headers->setCookie($cookie);
        $event->setResponse($response);
    }
}
