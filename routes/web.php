<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;

Route::get('/', [ContactController::class, 'index'])->name('contact.index');
Route::post('/contacts/confirm', [ContactController::class, 'confirm'])->name('contact.confirm');
Route::post('/contacts', [ContactController::class, 'store'])->name('contact.store');
Route::get('/thanks', [ContactController::class, 'thanks'])->name('contact.thanks');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');
    Route::get('/contacts/export', [AdminController::class, 'export'])->name('admin.export');
    Route::get('/admin/contacts/{id}', [AdminController::class, 'show'])->name('admin.show');
    Route::delete('/admin/contacts/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
});