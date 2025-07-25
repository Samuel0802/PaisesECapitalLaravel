<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;

//Start Game
route::get('/', [MainController::class, 'startGame'])->name('start_game');
route::post('/', [MainController::class, 'prepareGame'])->name('prepare_game');

route::get('/game', [MainController::class, 'game'])->name('game');
route::get('/answer/{answer}', [MainController::class, 'answer'])->name('answer');
