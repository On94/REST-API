<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeEmailRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\SignInRequest;
use App\Http\Requests\SignUpRequest;
use App\Http\Requests\VerifyEmailRequest;
use App\Http\Resources\ApiResponse;
use App\Services\UserAuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthenticationController extends Controller
{
    public function __construct(protected UserAuthenticationService $userService)
    {
    }

    /**
     * @param SignUpRequest $request
     * @return ApiResponse
     */
    public function signUp(SignUpRequest $request): ApiResponse
    {
        return ApiResponse::make([
            'data' => [
                $this->userService->store($request->validated())
            ]
        ]);

    }

    /**
     * @param SignInRequest $request
     * @return ApiResponse|JsonResponse
     */
    public function signIn(SignInRequest $request): ApiResponse|JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        if (!Auth::attempt($credentials)) {
            return ApiResponse::make([
                'status' => 'error',
                'message' => 'Unauthorized',
                'data' => 'Invalid credentials'
            ])->response()->setStatusCode(401);
        }
        return ApiResponse::make([
            'data' => [
                'id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'last_name' => Auth::user()->last_name,
                'email' => Auth::user()->email,
                'access_token' => $this->userService->createAccessToken(Auth::user()),
                'token_type' => 'bearer',
            ]
        ]);
    }

    /**
     * @param ForgotPasswordRequest $request
     * @return ApiResponse|JsonResponse
     */
    public function forgotPassword(ForgotPasswordRequest $request): ApiResponse|JsonResponse
    {
        if ($this->userService->sendForgotEmail($request->email)) {
            return ApiResponse::make(['message' => 'success']);
        }
        return ApiResponse::make(['status' => 'error', 'message' => 'Something went wrong'])->response()->setStatusCode(405);
    }

    /**
     * @param ResetPasswordRequest $request
     * @return ApiResponse|JsonResponse
     */
    public function resetPassword(ResetPasswordRequest $request): ApiResponse|JsonResponse
    {
        if ($this->userService->updatePassword($request->password_reset_token, $request->password)) {
            return ApiResponse::make(['status' => 'success']);
        }
        return ApiResponse::make(['status' => 'error', 'message' => 'Invalid or expired reset password token'])->response()->setStatusCode(422);

    }

    /**
     * @param Request $request
     * @return ApiResponse
     */
    public function resetPasswordLink(Request $request): ApiResponse
    {
        if ($this->userService->checkResetPasswordToken($request->token)) {
            return ApiResponse::make(['data' => ["password_reset_token" => $request->token]]);
        }
        return ApiResponse::make([
            'status' => 'error',
            'message' => 'Invalid or expired reset password token',
            'data' => 'Invalid credentials'
        ]);
    }

    /**
     * @param ChangeEmailRequest $request
     * @return ApiResponse
     */
    public function changeEmail(ChangeEmailRequest $request): ApiResponse
    {
        if ($this->userService->changeEmail(Auth::id(),$request->email)) {
            return ApiResponse::make(['status'=>'success','message'=>'success']);
        }
        return ApiResponse::make([
            'status' => 'error',
            'message' => 'Something went wrong',
            'data' => 'Invalid credentials'
        ]);

    }

    /**
     * @param VerifyEmailRequest $request
     * @return ApiResponse
     */
    public function verifyNewEmail(VerifyEmailRequest $request): ApiResponse
    {
        if ($this->userService->verifyEmail($request->code)) {
            return ApiResponse::make(['status'=>'success','message'=>'success']);
        }
        return ApiResponse::make([
            'status' => 'error',
            'message' => 'Expired',
            'data' => 'Invalid credentials'
        ]);
    }
}
