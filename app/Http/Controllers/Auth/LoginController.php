<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\Login\LoginRequest;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * 用户登录.
     *
     * @param \App\Http\Requests\Auth\Login\LoginRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $user = User::where('phone_number', $request->phone_number)->first();

        if (!optional($user)->checkPassword($request->password)) {
            // @todo 自定义异常
            abort(400, '用户名或密码错误');
        }

        $user->update([
            'last_login_ip' => $request->ip(),
            'last_login_at' => now(),
        ]);

        $token = $user->createToken('litchig');

        return response()->json([
            'code' => 0,
            'data' => [
                'token' => $token->plainTextToken,
            ],
            'message' => 'Login successfully',
        ]);
    }

    /**
     * 用户登出.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if ($user = $this->guard('airlock')->user()) {
            optional($user->currentAccessToken())->delete();
        }

        return response()->json([
            'code' => 0,
            'data' => '',
            'message' => 'Logout successfully',
        ]);
    }
}
