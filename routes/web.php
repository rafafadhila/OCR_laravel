<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OCRController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/ocr', [OCRController::class, 'showForm'])->name('ocr.form');
// Route::post('/ocr/process', [OCRController::class, 'processOCR'])->name('ocr.process');

Route::get('/', function () {
    return view('index'); // Replace 'index' with your actual view name
});

Route::get('/search', [OcrController::class, 'showOcrImage'])->name('ocr.search');

Route::post('/ocr', [OcrController::class, 'ocrImage'])->name('ocr.image');

