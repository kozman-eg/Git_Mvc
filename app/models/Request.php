<?php

namespace Models;

class Request
{
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }
    public static function posted(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && count($_POST) > 0) {
            return true;
        }
        return false;
    }
    public static function post(string $key, mixed $default = ''): mixed
    {
        if (empty($key)) {
            return $_POST;
        } else
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        return $default;
    }
    public static function files(string $key, mixed $default = ''): mixed
    {
        if (empty($key)) {
            return $_FILES;
        } else
        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        }
        return $default;
    }
    public static function get(string $get, mixed $default = ''): mixed
    {
        if (empty($key)) {
            return $_GET;
        } else
        if (isset($_GET[$get])) {
            return $_GET[$get];
        }
        return $default;
    }
    public static function input1(string $key, mixed $default = '', string $type = 'POST'): mixed
    {
        if (empty($key)) {
            return $_REQUEST;
        } else
        if (isset($_REQUEST[$key])) {
            return $_REQUEST[$key];
        }
        return $default;
    }

    public static function input(string $key, mixed $default = '', string $type = 'POST'): mixed
    {
        $source = strtoupper($type) === 'GET' ? $_GET : $_POST;
        return $source[$key] ?? $default;
    }

    public static function all(string $type = 'POST'): array
    {
        return strtoupper($type) === 'GET' ? $_GET : $_POST;
    }

    public static function has(string $key, string $type = 'POST'): bool
    {
        $source = strtoupper($type) === 'GET' ? $_GET : $_POST;
        return isset($source[$key]);
    }

    public static function old_value(string $key, mixed $default = '', string $type = 'POST'): mixed
    {
        return self::input($key, $default, $type);
    }

    public static function old_checked(string $key, string $value, string $default = '', string $type = 'POST'): string
    {
        $val = self::input($key, null, $type);
        if ($val !== null) {
            return $val == $value ? 'checked' : '';
        }
        return $default == $value ? 'checked' : '';
    }

    public static function old_select(string $key, mixed $value, mixed $default = '', string $type = 'POST'): string
    {
        $val = self::input($key, null, $type);
        if ($val !== null) {
            return $val == $value ? 'selected' : '';
        }
        return $default == $value ? 'selected' : '';
    }

    public static function is(string $method): bool
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === strtoupper($method);
    }
}
