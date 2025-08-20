<?php

use App\Livewire\Olimpiadas\Ranking;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Site\Index;
use App\Livewire\Site\Views;
use App\Livewire\Stats\Bosses;
use App\Livewire\Stats\ClasseRanking;
use App\Livewire\Stats\Heros;
use App\Livewire\Stats\Olimpiadas;
use App\Livewire\Stats\Sieges;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::get('/', Index::class)->name('home')->middleware(\App\Http\Middleware\TrackVisitorCount::class);
Route::any('{any}', function () {
    return redirect('/');
})->where('any', '.*');

Route::get('/views', Views::class)->name('views');

Route::get('olimpiadas', Olimpiadas::class)->name('olimpiadas');
Route::get('ranking', Ranking::class)->name('olimpiadas.ranking');
Route::get('olimpiadas/classe/{id}', ClasseRanking::class)->name('classe.ranking');
Route::get('heros', Heros::class)->name('heros');
Route::get('bosses', Bosses::class)->name('bosses');
Route::get('sieges', Sieges::class)->name('sieges');



Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
