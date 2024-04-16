<?php

namespace security;

use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtManager
{
    private $secretKey;
    private $issuedAt;
    private $expire;
    private $server;

    public function __construct(private $email, $exparation_min = "60")
    {
        $this->secretKey = $_ENV['SECRET_KEY'];
        $this->server = $_ENV['SERVER_NAME'];


        $this->issuedAt = new DateTimeImmutable();
        $this->expire = $this->issuedAt->modify("+$exparation_min minutes")->getTimestamp();
    }
    public function createToken()
    {
        $data = [
            'iat' => $this->issuedAt->getTimestamp(),
            'exp' => $this->expire,
            'iss' => $this->serverName,
            'nbf' => $this->issuedAt->getTimestamp(),
            'email' => $this->email
        ];

        return JWT::encode(
            $data,
            $this->secretKey,
            'HS512'
        );
    }
    public function validateToken($token, $email)
    {
    }
    public function decodeToken($token)
    {
        return JWT::decode($token, new Key($this->secretKey, 'HS256'));
    }

}