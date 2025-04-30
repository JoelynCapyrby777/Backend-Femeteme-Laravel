<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthService
{
    public function register(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'role_id' => $data['role_id'],
        ]);
    }

    public function login(array $credentials): string|false
    {
        try {
            return JWTAuth::attempt($credentials);
        } catch (JWTException $e) {
            return false;
        }
    }

    public function logout(): bool
    {
        try {
            JWTAuth::parseToken()->invalidate();
            return true;
        } catch (JWTException $e) {
            return false;
        }
    }

    public function me(): ?User
    {
        try {
            return JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            return null;
        }
    }

    public function refresh(): string|false
    {
        try {
            return JWTAuth::parseToken()->refresh();
        } catch (JWTException $e) {
            return false;
        }
    }
}
