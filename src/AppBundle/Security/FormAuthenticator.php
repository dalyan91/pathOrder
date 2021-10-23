<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class FormAuthenticator extends AbstractGuardAuthenticator
{

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * TokenAuthenticator constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser(). Returning null will cause this authenticator
     * to be skipped.
     *
     * @param Request $request
     * @return array|null
     * @throws \Exception
     */
    public function getCredentials(Request $request)
    {
        if ($request->isMethod('POST')) {
            return [
                'email' => $request->request->get('email'),
                'password' => $request->request->get('password'),
            ];
        }

        return null;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $accountProvider
     * @return UserInterface|null
     * @throws \Exception
     */
    public function getUser($credentials, UserProviderInterface $accountProvider)
    {
        return $accountProvider->loadUserByUsername($credentials['email']);
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $account
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $account)
    {
        $isValid = $this->passwordEncoder->isPasswordValid($account, $credentials['password']);

        if ($isValid === false) {
            throw new BadCredentialsException();
        }

        return true;
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return void
     * @throws \Exception
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new \Exception('Authentication failed.');
    }

    /**
     * Called when authentication is needed, but it's not sent
     *
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return void
     * @throws \Exception
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        throw new \Exception('Authentication required.',401);
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}