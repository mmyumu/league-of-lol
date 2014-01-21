<?php

namespace Lolol\UserBundle\LoginEntryPoint;

use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface, Symfony\Component\Security\Core\Exception\AuthenticationException, Symfony\Component\HttpFoundation\Request, Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Translation\Translator;

/**
 * When the user is not authenticated at all (i.e.
 * when the security context has no token yet),
 * the firewall's entry point will be called to start() the authentication process.
 */
class LoginEntryPoint implements AuthenticationEntryPointInterface {
	protected $router;
	protected $translator;
	
	/**
	 * Initialize injected dependencies
	 *
	 * @param Router $router        	
	 * @param Translator $translator        	
	 */
	public function __construct(Router $router, Translator $translator) {
		$this->router = $router;
		$this->translator = $translator;
	}
	
	/**
	 * This method receives the current Request object and the exception by which the exception listener was triggered.
	 * The method should return a Response object
	 *
	 * @param Request $request        	
	 * @param AuthenticationException $authException        	
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function start(Request $request, AuthenticationException $authException = null) {
		$session = $request->getSession();
		
		// I am choosing to set a FlashBag message with my own custom message.
		// Alternatively, you could use AuthenticaionException's generic message
		// by calling $authException->getMessage()
		$message = $this->translator->trans('user.notSignedIn');
		$session->getFlashBag()->add('error', $message);
		
		return new RedirectResponse($this->router->generate('lolol_app_homepage'));
	}
}