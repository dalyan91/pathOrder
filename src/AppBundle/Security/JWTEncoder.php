<?php

namespace AppBundle\Security;

use Namshi\JOSE\JWS;
use UnexpectedValueException;

class JWTEncoder
{
    /**
     * Hash algorithm
     */
    const ALG = 'HS256';

    /**
     * @var resource|string Symfony secret key in paramater file
     */
    private $key;

    /**
     * JWTEncoder constructor.
     *
     * @param resource|string $key Symfony secret key in paramater file
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Encode the JWT token
     *
     * @param array $payload
     * @param float|int $ttl Token expire time (seconds)
     * @return string Token key
     */
    public function encode(array $payload, $ttl = 2592000)
    {
        $payload['iat'] = time();
        $payload['exp'] = time() + $ttl;

        $jws = new JWS(['typ' => 'JWS', 'alg' => self::ALG]);
        $jws->setPayload($payload);
        $jws->sign($this->key);

        return $jws->getTokenString();
    }

    /**
     * Decode the JWT token
     *
     * @param string $token
     * @return array Return decoded JWT token
     * @throws UnexpectedValueException
     */
    public function decode($token)
    {
        $jws = JWS::load($token);

        if (!$jws->verify($this->key, self::ALG)) {
            throw new UnexpectedValueException('Invalid JWT');
        }

        if ($this->isExpired($payload = $jws->getPayload())) {
            throw new UnexpectedValueException('Expired JWT');
        }

        return $payload;
    }

    /**
     * Token is expired
     *
     * @param array $payload
     * @return bool
     */
    private function isExpired($payload)
    {
        if (isset($payload['exp']) && is_numeric($payload['exp'])) {
            return (time() - $payload['exp']) > 0;
        }

        return false;
    }
}