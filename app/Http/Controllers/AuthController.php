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
        if (!$guard->once($creds)) {
            return response()->setStatusCode(401);
        }

        $user = $guard->user();
        $authToken = $this->generateToken($user);
        return response([
            'message' => 'success',
            'token' => $authToken->getPlainTextToken()
        ]);
    }

    public function register(RegistrationRequest $request)
    {
        $user = new User();
        $user->username = $request->username;
        $user->password = $request->password;
        $user->email = $request->email;
        $user->save();

        $authToken = $this->generateToken($user);
        return response([
            'message' => 'success',
            'token' => $authToken->getPlainTextToken()
        ]);
    }

    private function generateToken(Authenticatable $user): AuthToken
    {
        $uniqueToken = Str::random();
        $encryptedToken = encrypt($uniqueToken);

        $authToken = new AuthToken();
        $authToken->token = $encryptedToken;
        $authToken->user()->associate($user);
        $authToken->save();

        $authToken->setPlainTextToken($uniqueToken);

        return $authToken;
    }
}
