<?php

    namespace Session;

    use \Exception;
    use \Lcobucci\JWT\Builder;
    use \Lcobucci\JWT\Parser;
    use \Lcobucci\JWT\Signer\Hmac\Sha256;
    use \Lcobucci\JWT\Signer\Key;

final class SessionToken
{
    private Key $key;
    private Sha256 $signer;

    public function __construct()
    {
        $this->key = new Key($_ENV['JWT_SECRET_KEY']);
        $this->signer = new Sha256();
    }
    
    public function createFor(string $username) : string
    {
        $time = time();
        $validForHours = (int) $_ENV['JWT_EXPIRY_HOURS'];
        $expiry = $time + (60 * 60 * $validForHours);

        $token = (new Builder())->issuedAt($time)
            ->canOnlyBeUsedAfter($time)
            ->expiresAt($expiry)
            ->withClaim('user', $username)
            ->getToken($this->signer, $this->key);
        
        return $token;
    }
}
