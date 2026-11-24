<?php

namespace App\Enums;

enum StatusPengiriman: string
{
    case Ditugaskan = 'ditugaskan';
    case Berangkat = 'berangkat';
    case Tiba = 'tiba';
    case Dibatalkan = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::Ditugaskan => 'Ditugaskan',
            self::Berangkat => 'Dalam Perjalanan',
            self::Tiba => 'Tiba di Venue',
            self::Dibatalkan => 'Dibatalkan',
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::Ditugaskan => 'mint',
            self::Berangkat => 'amber',
            self::Tiba => 'green',
            self::Dibatalkan => 'gray',
        };
    }
}
