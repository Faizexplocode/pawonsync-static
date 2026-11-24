<?php

namespace App\Enums;

enum StatusPembayaran: string
{
    case Menunggu = 'menunggu';
    case Berhasil = 'berhasil';
    case Gagal = 'gagal';

    public function label(): string
    {
        return match ($this) {
            self::Menunggu => 'Menunggu Pembayaran',
            self::Berhasil => 'Berhasil',
            self::Gagal => 'Gagal',
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::Menunggu => 'amber',
            self::Berhasil => 'green',
            self::Gagal => 'gray',
        };
    }
}
