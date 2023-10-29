<?php

namespace App\Services;

use App\Mail\EmailVerify;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class UserAuthenticationService
{
    /**
     * @var string
     */
    private string $resetPasswordToken;

    /**
     * @param UserRepository $userRepository
     */
    public function __construct(public UserRepository $userRepository)
    {
    }


    /**
     * @param array $data
     * @return User
     */
    public function store(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = now();

        return $this->userRepository->store($data);
    }

    /**
     * @param User $user
     * @return string
     */
    public function createAccessToken(User $user): string
    {
        return $user->createToken('AuthToken')->accessToken;
    }

    /**
     * @return void
     */
    private function generateResetPasswordToken(): void
    {
        $this->resetPasswordToken = base64_encode(Str::random(40)) . uniqid();
    }

    /**
     * Update reset password token and send email
     * @param string $email
     * @return bool
     */
    public function sendForgotEmail(string $email): bool
    {
        try {
            $this->generateResetPasswordToken();

            $this->userRepository->updateResetPasswordToken($email, $this->resetPasswordToken);

            Mail::to($email)->send(new ForgotPasswordMail($this->resetPasswordToken));

            return true;

        } catch (\Exception  $exception) {
            Log::error('Forgot Email Error : ' . $exception->getMessage());
        }
        return false;
    }

    /**
     * Check reset password token and update password
     * @param string $resetPasswordToken
     * @param string $password
     * @return bool
     */
    public function updatePassword(string $resetPasswordToken, string $password): bool
    {
        if ($this->checkResetPasswordToken($resetPasswordToken)) {

            $password = Hash::make($password);
            $this->userRepository->updatePassword($resetPasswordToken, $password);

            return true;
        }
        return false;
    }

    /**
     * @param string|null $resetPasswordToken
     * @return bool
     */
    public function checkResetPasswordToken(?string $resetPasswordToken): bool
    {
        return $this->userRepository->checkResetPasswordToken($resetPasswordToken);
    }

    /**
     * Generate a random 4-character code
     * Set code expiration time
     * Update email
     * @param int $userId
     * @param string $email
     * @return bool
     */
    public function changeEmail(int $userId, string $email): bool
    {
        $code = Str::random(4);
        $expiresAt = Carbon::now()->addMinutes(30);

        try {
            $this->userRepository->changeEmail($userId, $email, $code, $expiresAt);
            Mail::to($email)->send(new EmailVerify($code));

            return true;
        } catch (\Exception $exception) {
            Log::error('Email verify Error : ' . $exception->getMessage());
        }
        return false;
    }

    /**
     * Check verify code and verify new email
     * @param string $code
     * @return bool
     */
    public function verifyEmail(string $code): bool
    {
        if ($this->userRepository->checkVerifyCode(Auth::id(), $code)) {
            $this->userRepository->verifyEmail(Auth::id());
            return true;
        }
        return false;

    }
}
