<?php

return [
    'kategori' => [
        ['code' => 'NB', 'name' => 'Nasi Box'],
        ['code' => 'PR', 'name' => 'Prasmanan'],
        ['code' => 'SB', 'name' => 'Snack'],
        ['code' => 'TP', 'name' => 'Tumpeng'],
        ['code' => 'DS', 'name' => 'Dessert'],
    ],

    'min_porsi' => 30,

    'promo' => [
        'badge' => 'PROMO WISUDA',
        'title' => 'Diskon 20% paket 100+ porsi',
        'note' => 'Berlaku sampai 31 Des 2026 · Voucher WISUDA20',
    ],

    'flash_sale' => [
        'title' => 'Paket Arisan 50 porsi',
        'harga' => 1450000,
        'harga_normal' => 1900000,
        'terjual' => 72,
    ],

    'metode_pembayaran' => [
        ['code' => 'qris', 'name' => 'QRIS', 'note' => 'Scan dengan semua e-wallet', 'icon' => 'sliders'],
        ['code' => 'transfer', 'name' => 'Transfer Bank', 'note' => 'Transfer manual, verifikasi oleh seller', 'icon' => 'banknote'],
        ['code' => 'cod', 'name' => 'Bayar di Tempat (COD)', 'note' => 'Bayar saat pesanan tiba', 'icon' => 'wallet'],
    ],
];
