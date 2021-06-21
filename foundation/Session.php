<?php

declare(strict_types=1);

namespace Nitogram\Foundation;

class Session
{
    public const FLASH = "_flash";
    public const OLD = "_old";
    public const STATUS = "_status";
    public const ERRORS = "_errors";

    public static function start(): void
    {
        session_start();
    }

    public static function add(string $key, mixed $value, bool $isFlash = false): mixed
    {
        if ($isFlash) {
            return $_SESSION[static::FLASH][$key] = $value;
        }
        return $_SESSION[$key] = $value;
    }

    public static function addFlash(string $key, mixed $value): mixed
    {
        return static::add($key, $value, true);
    }

    public static function get(string $key, bool $isFlash = false): mixed
    {
        if ($isFlash) {
            return $_SESSION[static::FLASH][$key] ?? null;
        }
        return $_SESSION[$key] ?? null;
    }

    public static function getFlash(string $key): mixed
    {
        return static::get($key, true);
    }

    public static function delete(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        session_destroy();
    }

    public static function resetFlash(): void
    {
        $_SESSION[static::FLASH] = [];
    }

}