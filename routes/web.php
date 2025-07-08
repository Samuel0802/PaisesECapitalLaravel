<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;


route::view('/', 'home');
route::get('/show_data', [MainController::class, 'showData']);
