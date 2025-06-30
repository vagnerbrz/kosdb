<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Site\Index;
use App\Livewire\Site\Views;
use App\Livewire\Stats\Bosses;
use App\Livewire\Stats\Heros;
use App\Livewire\Stats\Olimpiadas;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::get('/', Index::class)->name('home')->middleware(\App\Http\Middleware\TrackVisitorCount::class);
Route::get('/views', Views::class)->name('views');

Route::get('olimpiadas', Olimpiadas::class)->name('olimpiadas');
Route::get('heros', Heros::class)->name('heros');
Route::get('bosses', Bosses::class)->name('bosses');

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
