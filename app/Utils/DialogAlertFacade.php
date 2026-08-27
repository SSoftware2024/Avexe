<?php

namespace App\Utils;

use App\Enum\Utils\DialogAlertType;

final class DialogAlertFacade
{
    private static function make(string $message, DialogAlertType $type, ?int $duration = null): array
    {
        return [
            'message' => $message,
            'type' => $type->value,
        ];
    }
    private static function createSession(array $data)
    {
        session()->flash(RESPONSE_DATA_KEY_INERTIA, [
            'alert_dialog' => $data
        ]);
    }

    public static function info(string $message, ?int $duration = null): array
    {
        $array = self::make($message, DialogAlertType::INFO, $duration);
        self::createSession($array);
        return $array;
    }
    public static function success(string $message, ?int $duration = null): array
    {
        $array = self::make($message, DialogAlertType::SUCCESS, $duration);
        self::createSession($array);
        return $array;
    }
    public static function warning(string $message, ?int $duration = null): array
    {
        $array = self::make($message, DialogAlertType::WARNING, $duration);
        self::createSession($array);
        return $array;
    }
    public static function error(string $message, ?int $duration = null): array
    {
        $array = self::make($message, DialogAlertType::ERROR, $duration);
        self::createSession($array);
        return $array;
    }
}
