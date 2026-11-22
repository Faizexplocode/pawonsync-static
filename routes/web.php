<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('auth.splash'))->name('splash');

Route::get('/screens', fn () => view('screens'))->name('screens');

Route::get('/login/{role}', function (string $role) {
    abort_unless(in_array($role, ['buyer', 'seller', 'kurir'], true), 404);

    return view('auth.login', ['role' => $role]);
})->name('login');

Route::prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/', fn () => view('buyer.beranda'))->name('beranda');
    Route::get('/katalog', fn () => view('buyer.katalog'))->name('katalog');
    Route::get('/filter', fn () => view('buyer.filter'))->name('filter');
    Route::get('/menu/{slug}', function (string $slug) {
        $menu = collect(config('demo.menus'))->firstWhere('slug', $slug);
        abort_unless($menu, 404);

        return view('buyer.menu', ['menu' => $menu]);
    })->name('menu');
    Route::get('/keranjang', fn () => view('buyer.keranjang'))->name('keranjang');
    Route::get('/checkout', fn () => view('buyer.checkout'))->name('checkout');
    Route::get('/pembayaran', fn () => view('buyer.pembayaran'))->name('pembayaran');
    Route::get('/konfirmasi', fn () => view('buyer.konfirmasi'))->name('konfirmasi');
    Route::get('/pesanan', fn () => view('buyer.pesanan'))->name('pesanan');
    Route::get('/pesanan/{id}', function (string $id) {
        $order = collect(config('demo.orders'))->firstWhere('id', $id) ?? config('demo.orders')[0];

        return view('buyer.pesanan-detail', ['order' => $order]);
    })->name('pesanan.detail');
    Route::get('/tracking', fn () => view('buyer.tracking'))->name('tracking');
    Route::get('/tiba', fn () => view('buyer.tiba'))->name('tiba');
    Route::get('/ulasan', fn () => view('buyer.ulasan'))->name('ulasan');
    Route::get('/notifikasi', fn () => view('buyer.notifikasi'))->name('notifikasi');
    Route::get('/profil', fn () => view('buyer.profil'))->name('profil');
});

Route::prefix('seller')->name('seller.')->group(function () {
    Route::get('/', fn () => view('seller.dashboard'))->name('dashboard');
    Route::get('/pesanan', fn () => view('seller.pesanan'))->name('pesanan');
    Route::get('/pesanan/{id}', function (string $id) {
        $order = collect(config('demo.seller_orders'))->firstWhere('id', $id) ?? config('demo.seller_orders')[0];

        return view('seller.pesanan-detail', ['order' => $order]);
    })->name('pesanan.detail');
    Route::get('/milestone', fn () => view('seller.milestone'))->name('milestone');
    Route::get('/packaging', fn () => view('seller.packaging'))->name('packaging');
    Route::get('/kurir', fn () => view('seller.kurir'))->name('kurir');
    Route::get('/katalog', fn () => view('seller.katalog'))->name('katalog');
    Route::get('/menu', fn () => view('seller.menu-form'))->name('menu');
    Route::get('/kalkulasi', fn () => view('seller.kalkulasi'))->name('kalkulasi');
    Route::get('/kalender', fn () => view('seller.kalender'))->name('kalender');
    Route::get('/keuangan', fn () => view('seller.keuangan'))->name('keuangan');
    Route::get('/promosi', fn () => view('seller.promosi'))->name('promosi');
    Route::get('/pengaturan', fn () => view('seller.pengaturan'))->name('pengaturan');
    Route::get('/retur', fn () => view('seller.retur'))->name('retur');
});

Route::prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/', fn () => view('kurir.beranda'))->name('beranda');
    Route::get('/tugas', fn () => view('kurir.tugas'))->name('tugas');
    Route::get('/tugas/{id}', function (string $id) {
        $task = collect(config('demo.tasks'))->firstWhere('id', $id) ?? config('demo.tasks')[0];

        return view('kurir.tugas-detail', ['task' => $task]);
    })->name('tugas.detail');
    Route::get('/departure', fn () => view('kurir.departure'))->name('departure');
    Route::get('/live', fn () => view('kurir.live'))->name('live');
    Route::get('/bukti', fn () => view('kurir.bukti'))->name('bukti');
});
