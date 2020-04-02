<?php


namespace App\Services\Auth;


class AuthorizationHeaderToken
{

    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $encryptedToken;

    public function __construct(string $id, string $token)
    {
        $this->id = $id;
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}
