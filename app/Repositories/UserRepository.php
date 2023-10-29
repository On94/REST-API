<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Carbon;

class UserRepository
{
    public function __construct(protected User $model)
    {
    }

    /**
     * @param array $data
     * @return User
     */
    public function store(array $data): User
    {
        return $this->model->create($data);
    }

    /**
     * @param int $userId
     * @param array $data
     * @return User
     */
    public function edit(int $userId, array $data): User
    {
        return tap($this->model->where('user_id', $userId)->update($data)->first());
    }


    public function updateResetPasswordToken(string $email, string $token): int
    {
        return $this->model->where('email', $email)->update(['password_reset_token' => $token]);
    }


    public function checkResetPasswordToken(?string $resetPasswordToken): bool
    {
        return $this->model->where('password_reset_token', $resetPasswordToken)->exists();
    }

    /**
     * @param string $resetPasswordToken
     * @param string $password
     * @return int
     */
    public function updatePassword(string $resetPasswordToken, string $password): int
    {
        return $this->model->where('password_reset_token', $resetPasswordToken)->update(['password' => $password, 'password_reset_token' => null]);
    }

    /**
     * @param int $userId
     * @param string $email
     * @param string $code
     * @param Carbon $expiresAt
     * @return int
     */
    public function changeEmail(int $userId, string $email, string $code, Carbon $expiresAt): int
    {
        return $this->model->where('id', $userId)->update([
            'email' => $email,
            'verify_code' => $code,
            'verify_code_expires_at' => $expiresAt
        ]);
    }

    /**
     * @param int $userId
     * @param $code
     * @return User|null
     */
    public function checkVerifyCode(int $userId, $code): ?User
    {
        return $this->model->where('id', $userId)
            ->where('verify_code', $code)
            ->where('verify_code_expires_at', '<', now())
            ->first();
    }

    /**
     * @param int $userId
     * @return int
     */
    public function verifyEmail(int $userId):int
    {
        return $this->model->where('id', $userId)->update([
            'verify_code_expires_at'=>null,
            'verify_code'=>null,
            'email_verified_at'=>now()
        ]);

    }
}
