<?php

namespace App\Enums;

enum StatusMilestone: string
{
    case Dikonfirmasi = 'dikonfirmasi';
    case Belanja = 'belanja';
    case Memasak = 'memasak';
    case Packing = 'packing';
    case SiapKirim = 'siap_kirim';

    public function label(): string
    {
        return match ($this) {
            self::Dikonfirmasi => 'Pesanan Dikonfirmasi',
            self::Belanja => 'Belanja Bahan',
            self::Memasak => 'Memasak',
            self::Packing => 'Packing',
            self::SiapKirim => 'Siap Kirim',
        };
    }

    /** @return array<int, string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
