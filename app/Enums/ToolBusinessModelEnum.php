<?php

namespace App\Enums;

enum ToolBusinessModelEnum: string
{
    case FREE = 'free';
    case FREEMIUM = 'freemium';
    case PAID = 'paid';
    case SUBSCRIPTION = 'subscription';
    case ONE_TIME = 'one_time';
    case OPEN_SOURCE = 'open_source';

    public function label(): string
    {
        return match ($this) {
            self::FREE => 'Gratis',
            self::FREEMIUM => 'Freemium',
            self::PAID => 'Pago',
            self::SUBSCRIPTION => 'Suscripción',
            self::ONE_TIME => 'Pago único',
            self::OPEN_SOURCE => 'Código abierto',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::FREE => 'green',
            self::FREEMIUM => 'blue',
            self::PAID => 'red',
            self::SUBSCRIPTION => 'purple',
            self::ONE_TIME => 'orange',
            self::OPEN_SOURCE => 'cyan',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function labels(): array
    {
        $labels = [];
        foreach (self::cases() as $case) {
            $labels[$case->value] = $case->label();
        }

        return $labels;
    }
}
