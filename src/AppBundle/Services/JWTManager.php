<?php

namespace AppBundle\Services;

use Firebase\JWT\JWT;

class JWTManager
{

    /**
     * @var string
     */
    private $privateKey;

    /**
     * @var string
     */
    private $algorithm = 'HS256';

    /**
     * JWTManager constructor.
     *
     * @param $privateKey
     */
    public function __construct($privateKey)
    {
        $this->privateKey = $privateKey;
    }

    /**
     * @param array $payload
     * @return string
     */
    public function encode(array $payload)
    {
        return JWT::encode($payload, $this->privateKey, $this->algorithm);
    }

    /**
     * @param $crypted
     * @return object
     */
    public function decode($crypted)
    {
        return JWT::decode($crypted, $this->privateKey, [$this->algorithm]);
    }
}