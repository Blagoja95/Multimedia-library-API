<?php

namespace security;

use DateTimeImmutable;
use DateTimeZone;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtManager
{
    private $secretKey;
    private $issuedAt;
    private $expire;
    private $serverName;

    public function __construct($exparation_min = "60")
    {
        $this->secretKey = $_ENV['SECRET_KEY'];
        $this->serverName = $_ENV['SERVER_NAME'];


        $this->issuedAt = new DateTimeImmutable("now", new DateTimeZone("UTC"));
        $this->expire = $this->issuedAt->modify("+$exparation_min minutes")->getTimestamp();
    }

    public function createToken($email)
    {
        $data = [
            'iat' => $this->issuedAt->getTimestamp(),
            'exp' => $this->expire,
            'iss' => $this->serverName,
            'nbf' => $this->issuedAt->getTimestamp(),
            'email' => $email
        ];

        return JWT::encode(
            $data,
            $this->secretKey,
            'HS512'
        );
    }

    public function decodeToken($token)
    {
        try
        {
            return JWT::decode($token, new Key($this->secretKey, 'HS512'));
        }
        catch (ExpiredException $e)
        {
            return [false, "error" => $e->getMessage()];
        }
    }

}