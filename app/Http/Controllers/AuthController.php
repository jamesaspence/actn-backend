<?php


namespace App\Http\Controllers;


use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
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
            'token' => $authToken->token
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
            'token' => $authToken->token
        ]);
    }

    private function generateToken(Authenticatable $user): AuthToken
    {
        $uniqueToken = Str::random();

        $authToken = new AuthToken();
        $authToken->token = $uniqueToken;
        $authToken->user()->associate($user);
        $authToken->save();

        return $authToken;
    }
}
