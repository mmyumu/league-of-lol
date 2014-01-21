<?php

namespace Lolol\UserBundle\Handler;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Translation\Translator;

/**
 *
 * @author mlabetaa
 *        
 */
class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface {
	private $router;
	private $translator;
	
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
	 * Handler when success authentication
	 *
	 * @param Request $request        	
	 * @param TokenInterface $token        	
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse
	 */
	public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
		// If the user tried to access a protected resource and was forces to login
		// redirect him back to that resource
		if ($targetPath = $request->getSession()->get('_security.target_path')) {
			$url = $targetPath;
		}
		else {
			// Otherwise, redirect him to wherever you want
			$url = $this->router->generate('lolol_app_homepage');
		}
		
		$result = array(
				'success' => true,
				'function' => 'onAuthenticationSuccess',
				'url' => $url);
		$response = new Response(json_encode($result));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}
	
	/**
	 * Handler when failure authentication
	 *
	 * @param Request $request        	
	 * @param AuthenticationException $exception        	
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
		$result = array(
				'success' => false,
				'function' => 'onAuthenticationFailure',
				'message' => $this->translator->trans($exception->getMessage(), array(), 'FOSUserBundle'));
		$response = new Response(json_encode($result));
		$response->headers->set('Content-Type', 'application/json');
		return $response;
	}
}