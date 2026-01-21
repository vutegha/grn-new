<?php
use Illuminate\Support\Facades\Route;

// Test simple pour vérifier que les routes se chargent
Route::get('/', function () {
    return 'Accueil - Routes OK';
});

Route::get('/test-db', function () {
    try {
        $count = DB::table('actualites')->count();
        return "Database OK - Actualités: $count enregistrements";
    } catch (Exception $e) {
        return "Erreur DB: " . $e->getMessage();
    }
});

// Routes admin avec authentification
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return 'Dashboard Admin (authentifié)';
    })->name('dashboard');
    
    Route::get('/test', function () {
        return 'Test Admin OK';
    })->name('test');
});

// Routes d'authentification
Route::get('/login', function () {
    return 'Page de connexion';
})->name('login');

Route::post('/login', function () {
    return 'Traitement connexion';
});

Route::middleware(['auth'])->post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');
