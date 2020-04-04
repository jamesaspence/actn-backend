<?php


namespace App\Services\Auth;


use App\Models\AuthToken;
use Illuminate\Auth\GuardHelpers;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuthTokenGuard implements Guard
{
    use GuardHelpers;
    /**
     * @var Request
     */
    private $request;

    private $authFailed = false;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->setProvider($provider);
        $this->request = $request;
    }


    /**
     * @inheritDoc
     */
    public function user()
    {
        if ($this->authFailed || !is_null($this->user)) {
            return $this->user;
        }

        $token = $this->getTokenFromRequest();

        if (is_null($token)) {
            return $this->authFailed();
        }

        $user = $this->provider->retrieveById($token->getId());
        if (is_null($user)) {
            return $this->authFailed();
        }

        if (!$this->authTokenExists($user, $token)) {
            return $this->authFailed();
        }

        return $this->user = $user;
    }

    /**
     * @inheritDoc
     */
    public function validate(array $credentials = [])
    {
        $user = $this->provider->retrieveByCredentials($credentials);
        if(is_null($user)) {
            return false;
        }

        $validated = $this->provider->validateCredentials($user, $credentials);

        if ($validated) {
            $this->setUser($user);
        }

        return $validated;
    }

    private function getTokenFromRequest(): ?AuthorizationHeaderToken
    {
        if (!$this->request->hasHeader('Authorization')) {
            return null;
        }

        $headerVal = $this->request->header('Authorization');

        if (!Str::startsWith($headerVal, 'Basic ')) {
            return null;
        }

        $decoded = explode(':', base64_decode(Str::after($headerVal, 'Basic ')));

        if (count($decoded) !== 2) {
            return null;
        }

        return new AuthorizationHeaderToken($decoded[0], $decoded[1]);
    }

    private function authFailed()
    {
        $this->authFailed = true;
        return null;
    }

    private function authTokenExists(Authenticatable $user, AuthorizationHeaderToken $token): bool
    {
        /*
         * TODO any way for us to secure these tokens in DB?
         * TODO If we encrypt, we'll need to worry about loading tons of tokens to decrypt and test against for login
         * TODO Maybe we generate a base 64 token and store that in DB w/ encrypted value and id?
         * TODO Then that base 64 code is always the same presumably?
         */
        return AuthToken::query()
            ->where('user_id', '=', $user->getAuthIdentifier())
            ->where('token', '=', $token->getToken())
            ->exists();
    }
}
