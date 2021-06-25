<?php

declare(strict_types=1);

namespace Nitogram\Foundation;

use App\Models\User;

class Authentication
{
    protected const SESSION_ID = "user_id";

    public static function check(): bool
    {
        return (bool)static::id();
    }

    public static function checkIsAdmin(): bool
    {
        return static::check() && static::getUser()->getRole() === "admin";
    }

    public static function getUser(): ?User
    {
        return User::find(static::id());
    }

    public static function id(): ?int
    {
        return Session::get(static::SESSION_ID);
    }

    public static function verify(string $email, string $password): bool
    {
        $user = User::where("email", $email)->fisrt();
        return $user && password_verify($password, $user->getPassword());
    }

    public static function logout(): void
    {
        Session::delete(static::SESSION_ID);
    }

    public static function authenticate(int $id): void
    {
        Session::add(static::SESSION_ID, $id);
    }

}