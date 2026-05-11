<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DomainController;
use App\Http\Controllers\ConceptController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('domains', DomainController::class)->except(['show']);

    Route::get('/domains/{domain}/concepts', [ConceptController::class, 'index'])->name('domains.concepts.index');
    Route::get('/domains/{domain}/concepts/create', [ConceptController::class, 'create'])->name('domains.concepts.create');
    Route::post('/domains/{domain}/concepts', [ConceptController::class, 'store'])->name('domains.concepts.store');
    Route::get('/domains/{domain}/concepts/{concept}', [ConceptController::class, 'show'])->name('domains.concepts.show');
    Route::get('/domains/{domain}/concepts/{concept}/edit', [ConceptController::class, 'edit'])->name('domains.concepts.edit');
    Route::put('/domains/{domain}/concepts/{concept}', [ConceptController::class, 'update'])->name('domains.concepts.update');
    Route::delete('/domains/{domain}/concepts/{concept}', [ConceptController::class, 'destroy'])->name('domains.concepts.destroy');

    Route::patch('/concepts/{concept}/status', [ConceptController::class, 'updateStatus'])->name('concepts.status');

    Route::get('/concepts/archived', [ConceptController::class, 'archived'])->name('concepts.archived');
    Route::post('/concepts/{concept}/restore', [ConceptController::class, 'restore'])->name('concepts.restore');
    Route::delete('/concepts/{concept}/force-delete', [ConceptController::class, 'forceDelete'])->name('concepts.force-delete');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';