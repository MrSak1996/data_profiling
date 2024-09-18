<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataProfilingController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/import_excel', [DataProfilingController::class, 'saveExcelData']);

Route::middleware('api')->group(function () {
    Route::get('/uploaded-files', [DataProfilingController::class, 'uploadedFiles']);
    Route::get('/countUploadedFiles', [DataProfilingController::class, 'countUploadedFiles']);
    Route::get('/getInvalidData', [DataProfilingController::class, 'getInvalidData']);
    Route::get('/getOnbintStaging', [DataProfilingController::class, 'getOnbintStaging']);
    Route::get('/checkDataMatches', [DataProfilingController::class, 'checkDataMatches']);
    Route::get('/getUnmatchData', [DataProfilingController::class, 'getUnmatchData']);
    Route::get('/getMatchData', [DataProfilingController::class, 'getMatchData']);
    Route::get('/exportUnmatch', [DataProfilingController::class, 'exportUnmatch']);
    Route::get('/getDuplicateDataStaging', [DataProfilingController::class, 'getDuplicateDataStaging']);
    Route::get('/findDuplicates', [DataProfilingController::class, 'findDuplicates']);

});
