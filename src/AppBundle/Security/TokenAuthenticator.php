<?php

namespace AppBundle\Security;

use AppBundle\Entity\Account;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var JWTEncoder
     */
    private $jwtEnoder;

    /**
     * TokenAuthenticator constructor.
     *
     * @param JWTEncoder $jwtEnoder
     */
    public function __construct(JWTEncoder $jwtEnoder)
    {
        $this->jwtEnoder = $jwtEnoder;
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
        if ($request->headers->has('Authorization')) {
            $headerParts = explode(' ', $request->headers->get('Authorization'));

            if (! (count($headerParts) === 2 && $headerParts[0] === 'Bearer')) {
                throw new \Exception('Malformed Authorization Header');
            }

            return $headerParts[1];
        }

        return null;
    }

    /**
     * Get logged account in JWT
     *
     * @param mixed $credentials
     * @param UserProviderInterface $accountProvider
     * @return UserInterface|null
     * @throws \Exception
     */
    public function getUser($credentials, UserProviderInterface $accountProvider)
    {
        try {
            $payload = $this->jwtEnoder->decode($credentials);
        } catch (\Exception $e) {
            throw new \Exception('Invalid JWT');
        }

        if (! isset($payload['token'])) {
            throw new \Exception('Invalid JWT');
        }

        /** @var AccountProvider $accountProvider */
        /** @var Account $account */
        $account = $accountProvider->loadUserBySessionToken($payload['token']);

        return $account;
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
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
     * If auth failed, fire the forbidden exception
     *
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
     * Not support
     *
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}