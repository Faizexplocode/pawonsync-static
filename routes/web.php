<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BuyerController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('auth.splash'))->name('splash');

Route::get('/screens', fn () => view('screens'))->name('screens');

Route::get('/login/{role}', [AuthController::class, 'create'])->name('login');
Route::post('/login/{role}', [AuthController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

Route::middleware('role:buyer')->prefix('buyer')->name('buyer.')->group(function () {
    Route::get('/', [BuyerController::class, 'beranda'])->name('beranda');
    Route::get('/katalog', [BuyerController::class, 'katalog'])->name('katalog');
    Route::get('/filter', [BuyerController::class, 'filter'])->name('filter');
    Route::get('/menu/{menu}', [BuyerController::class, 'menu'])->name('menu');

    Route::get('/keranjang', [BuyerController::class, 'keranjang'])->name('keranjang');
    Route::post('/keranjang', [BuyerController::class, 'tambahKeranjang'])->name('keranjang.tambah');
    Route::patch('/keranjang', [BuyerController::class, 'updateKeranjang'])->name('keranjang.update');
    Route::delete('/keranjang', [BuyerController::class, 'hapusKeranjang'])->name('keranjang.hapus');

    Route::get('/checkout', [BuyerController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [BuyerController::class, 'simpanCheckout'])->name('checkout.store');

    Route::get('/pembayaran/{pesanan}', [BuyerController::class, 'pembayaran'])->name('pembayaran');
    Route::post('/pembayaran/{pesanan}', [BuyerController::class, 'bayar'])->name('pembayaran.bayar');
    Route::get('/konfirmasi/{pesanan}', [BuyerController::class, 'konfirmasi'])->name('konfirmasi');

    Route::get('/pesanan', [BuyerController::class, 'pesanan'])->name('pesanan');
    Route::get('/pesanan/{pesanan}', [BuyerController::class, 'pesananDetail'])->name('pesanan.detail');

    Route::get('/tracking/{pesanan}', [BuyerController::class, 'tracking'])->name('tracking');
    Route::get('/tiba/{pesanan}', [BuyerController::class, 'tiba'])->name('tiba');
    Route::post('/tiba/{pesanan}', [BuyerController::class, 'konfirmasiTiba'])->name('tiba.konfirmasi');

    Route::get('/ulasan/{pesanan}', [BuyerController::class, 'ulasan'])->name('ulasan');
    Route::post('/ulasan/{pesanan}', [BuyerController::class, 'simpanUlasan'])->name('ulasan.store');

    Route::get('/notifikasi', [BuyerController::class, 'notifikasi'])->name('notifikasi');
    Route::post('/notifikasi/dibaca', [BuyerController::class, 'tandaiNotifikasi'])->name('notifikasi.dibaca');

    Route::get('/profil', [BuyerController::class, 'profil'])->name('profil');
    Route::patch('/profil', [BuyerController::class, 'updateProfil'])->name('profil.update');
});

Route::middleware('role:seller')->prefix('seller')->name('seller.')->group(function () {
    Route::get('/', [SellerController::class, 'dashboard'])->name('dashboard');

    Route::get('/pesanan', [SellerController::class, 'pesanan'])->name('pesanan');
    Route::get('/pesanan/{pesanan}', [SellerController::class, 'pesananDetail'])->name('pesanan.detail');
    Route::post('/pesanan/{pesanan}/konfirmasi', [SellerController::class, 'konfirmasiPesanan'])->name('pesanan.konfirmasi');
    Route::post('/pesanan/{pesanan}/tolak', [SellerController::class, 'tolakPesanan'])->name('pesanan.tolak');

    Route::get('/pesanan/{pesanan}/milestone', [SellerController::class, 'milestone'])->name('milestone');
    Route::post('/pesanan/{pesanan}/milestone', [SellerController::class, 'simpanMilestone'])->name('milestone.store');

    Route::get('/pesanan/{pesanan}/packaging', [SellerController::class, 'packaging'])->name('packaging');
    Route::post('/pesanan/{pesanan}/packaging', [SellerController::class, 'simpanPackaging'])->name('packaging.store');

    Route::get('/pesanan/{pesanan}/kurir', [SellerController::class, 'kurir'])->name('kurir');
    Route::post('/pesanan/{pesanan}/kurir', [SellerController::class, 'tugaskanKurir'])->name('kurir.tugaskan');

    Route::get('/katalog', [SellerController::class, 'katalog'])->name('katalog');
    Route::get('/menu/{menu?}', [SellerController::class, 'menuForm'])->name('menu');
    Route::post('/menu/{menu?}', [SellerController::class, 'simpanMenu'])->name('menu.store');
    Route::delete('/menu/{menu}', [SellerController::class, 'hapusMenu'])->name('menu.hapus');

    Route::get('/kalkulasi', [SellerController::class, 'kalkulasi'])->name('kalkulasi');
    Route::get('/kalender', [SellerController::class, 'kalender'])->name('kalender');
    Route::get('/keuangan', [SellerController::class, 'keuangan'])->name('keuangan');
    Route::get('/promosi', [SellerController::class, 'promosi'])->name('promosi');
    Route::get('/pengaturan', [SellerController::class, 'pengaturan'])->name('pengaturan');
    Route::patch('/pengaturan', [SellerController::class, 'updatePengaturan'])->name('pengaturan.update');
    Route::get('/retur', [SellerController::class, 'retur'])->name('retur');
});

Route::middleware('role:kurir')->prefix('kurir')->name('kurir.')->group(function () {
    Route::get('/', [KurirController::class, 'beranda'])->name('beranda');
    Route::get('/tugas', [KurirController::class, 'tugas'])->name('tugas');
    Route::get('/tugas/{pengiriman}', [KurirController::class, 'tugasDetail'])->name('tugas.detail');

    Route::get('/departure/{pengiriman}', [KurirController::class, 'departure'])->name('departure');
    Route::post('/departure/{pengiriman}', [KurirController::class, 'mulaiBerangkat'])->name('departure.mulai');

    Route::get('/live/{pengiriman}', [KurirController::class, 'live'])->name('live');
    Route::post('/live/{pengiriman}', [KurirController::class, 'kirimLokasi'])->name('live.lokasi');

    Route::get('/bukti/{pengiriman}', [KurirController::class, 'bukti'])->name('bukti');
    Route::post('/bukti/{pengiriman}', [KurirController::class, 'simpanBukti'])->name('bukti.store');
});
