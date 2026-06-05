<?php

use App\Livewire\Kategori\Index as KategoriIndex;
use App\Livewire\Laporan\Index as LaporanIndex;
use App\Livewire\Member\Index as MemberIndex;
use App\Livewire\Menu\Index as MenuIndex;
use App\Livewire\Pengeluaran\Index as PengeluaranIndex;
use App\Livewire\Pesanan\Index as PesananIndex;
use App\Livewire\Stok\Index as StokIndex;
use App\Livewire\Voucher\Index as VoucherIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // Manajemen Menu
    Route::prefix('menu')->name('menu.')->group(function () {
        Route::get('/', MenuIndex::class)->name('index');
        Route::view('/tambah', 'pages.menu.create')->name('create');
        Route::view('/edit/{id}', 'pages.menu.edit')->name('edit');
    });

    // Kategori
    Route::get('kategori', KategoriIndex::class)->name('kategori.index');

    // Transaksi
    Route::get('pesanan', PesananIndex::class)->name('pesanan.index');
    Route::get('member', MemberIndex::class)->name('member.index');

    // Operasional
    Route::get('stok', StokIndex::class)->name('stok.index');
    Route::get('pengeluaran', PengeluaranIndex::class)->name('pengeluaran.index');
    Route::get('voucher', VoucherIndex::class)->name('voucher.index');

    // Laporan
    Route::get('laporan', LaporanIndex::class)->name('laporan.index');

});

require __DIR__.'/settings.php';
