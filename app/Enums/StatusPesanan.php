<?php

namespace App\Enums;

enum StatusPesanan: string
{
    case MenungguPembayaran = 'menunggu_pembayaran';
    case Dibayar = 'dibayar';
    case Dikonfirmasi = 'dikonfirmasi';
    case Diproses = 'diproses';
    case SiapKirim = 'siap_kirim';
    case Dikirim = 'dikirim';
    case Selesai = 'selesai';
    case Dibatalkan = 'dibatalkan';

    public function label(): string
    {
        return match ($this) {
            self::MenungguPembayaran => 'Menunggu Pembayaran',
            self::Dibayar => 'Sudah Dibayar',
            self::Dikonfirmasi => 'Dikonfirmasi',
            self::Diproses => 'Sedang Dimasak',
            self::SiapKirim => 'Siap Kirim',
            self::Dikirim => 'Dikirim',
            self::Selesai => 'Selesai',
            self::Dibatalkan => 'Dibatalkan',
        };
    }

    public function tone(): string
    {
        return match ($this) {
            self::MenungguPembayaran, self::Dibayar => 'amber',
            self::Dikonfirmasi, self::Diproses => 'mint',
            self::SiapKirim, self::Dikirim => 'green',
            self::Selesai => 'dark',
            self::Dibatalkan => 'gray',
        };
    }

    public function progress(): int
    {
        return match ($this) {
            self::MenungguPembayaran, self::Dibatalkan => 0,
            self::Dibayar, self::Dikonfirmasi => 1,
            self::Diproses => 2,
            self::SiapKirim => 3,
            self::Dikirim, self::Selesai => 4,
        };
    }

    public function canTransitionTo(self $next): bool
    {
        $flow = [
            self::MenungguPembayaran->value => [self::Dibayar, self::Dibatalkan],
            self::Dibayar->value => [self::Dikonfirmasi, self::Dibatalkan],
            self::Dikonfirmasi->value => [self::Diproses, self::Dibatalkan],
            self::Diproses->value => [self::SiapKirim, self::Dibatalkan],
            self::SiapKirim->value => [self::Dikirim, self::Dibatalkan],
            self::Dikirim->value => [self::Selesai],
            self::Selesai->value => [],
            self::Dibatalkan->value => [],
        ];

        return in_array($next, $flow[$this->value], true);
    }

    /** @return array<int, string> */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
