<?php

namespace Oro\Bundle\NavigationBundle\Twig;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;

class HashNavExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * Listen to the 'kernel.request' event to get the main request.
     * The request can not be injected directly into a Twig extension,
     * this causes a ScopeWideningInjectionException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->getRequestType() === HttpKernel::MASTER_REQUEST) {
            $this->request = $event->getRequest();
        }
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return array(
            'oro_is_hash_navigation' => new \Twig_Function_Method(
                $this,
                'checkIsHashNavigation',
                array('is_safe' => array('html'))
            ),
        );
    }

    /**
     * Check for hash navigation
     *
     * @return bool
     */
    public function checkIsHashNavigation()
    {
        return (!is_object($this->request)
            || (
                $this->request->headers->get('x-oro-hash-navigation') != true
                    && $this->request->get('x-oro-hash-navigation') != true
            )
        ) ? false : true;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'oro_hash_nav';
    }
}
