<?php
/**
 * Created by PhpStorm.
 * User: 6666
 * Date: 2018/2/25
 * Time: 16:07
 */

namespace App\AdminApi\Controllers;


class AuthController extends Controller
{
    public function login()
    {
        $credentials = request(['username', 'password']);

        if (!$token = auth($this->guard)->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }

    public function logout()
    {
        auth($this->guard)->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth($this->guard)->refresh());
    }

    public function me()
    {
        $user = auth($this->guard)->user();
        return response()->json($user->makeHidden(['password','remember_token']));
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth($this->guard)->factory()->getTTL() * 60
        ]);
    }
}