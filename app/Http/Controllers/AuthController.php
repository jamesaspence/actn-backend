<?php


namespace App\Http\Controllers;


use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Http\Resources\AuthenticatedUserResource;
use App\Models\AuthToken;
use App\Models\User;
use Illuminate\Auth\AuthManager;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * @var AuthManager
     */
    private $authManager;
    /**
     * @var Hasher
     */
    private $hasher;

    public function __construct(AuthManager $authManager, Hasher $hasher)
    {
        $this->authManager = $authManager;
        $this->hasher = $hasher;
    }

    public function login(LoginRequest $request)
    {
        $creds = $request->only('email', 'password');

        $guard = $this->authManager->guard();
        if (!$guard->validate($creds)) {
            return response(null, 401);
        }

        $user = $guard->user();
        $authToken = $this->generateToken($user);
        return response([
            'message' => 'success',
            'token' => $authToken->token,
            'user' => new AuthenticatedUserResource($user)
        ]);
    }

    public function register(RegistrationRequest $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->password = $this->hasher->make($request->password);
        $user->email = $request->email;
        $user->save();

        $authToken = $this->generateToken($user);
        return response([
            'message' => 'success',
            'token' => $authToken->token,
            'user' => new AuthenticatedUserResource($user)
        ]);
    }

    public function logout()
    {
        /** @var User $user */
        $user = $this->authManager->guard()->user();

        try {
            $user->getCurrentToken()->delete();
        } catch (\Exception $e) {
            //TODO log out error
        }
        return response(null, 204);
    }

    private function generateToken(Authenticatable $user): AuthToken
    {
        $uniqueToken = base64_encode(Str::random(36));

        if ($this->tokenAlreadyExists($uniqueToken)) {
            return $this->generateToken($user);
        }

        $authToken = new AuthToken();
        $authToken->token = $uniqueToken;
        $authToken->user()->associate($user);
        $authToken->save();

        return $authToken;
    }

    private function tokenAlreadyExists($token)
    {
        return AuthToken::query()
            ->where('token', '=', $token)
            ->exists();
    }
}
