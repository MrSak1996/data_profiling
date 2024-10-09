<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataProfilingController;
use App\Http\Controllers\UserController;


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
Route::middleware('auth:api')->group(function () {
    Route::get('/authenticated', function (Request $request) {
        return response()->json(['authenticated' => true]);
    });
});

Route::post('/logout', [UserController::class, 'logout']);



Route::post('login',[UserController::class,'login']);
Route::post('/createUser',[UserController::class,'createUser']);
Route::post('/import_excel', [DataProfilingController::class, 'saveExcelData']);
Route::post('/checkValidation', [DataProfilingController::class, 'checkValidation']);


Route::middleware('api')->group(function () {
    Route::get('/uploaded-files', [DataProfilingController::class, 'uploadedFiles']);
    Route::get('/countUploadedFiles', [DataProfilingController::class, 'countUploadedFiles']);
    Route::get('/getFiles', [DataProfilingController::class, 'getFiles']);
    Route::get('/getInvalidData', [DataProfilingController::class, 'getInvalidData']);
    Route::get('/getOnbintInvalid', [DataProfilingController::class, 'getOnbintInvalid']);
    Route::get('/getOnbintStaging', [DataProfilingController::class, 'getOnbintStaging']);
    Route::get('/checkDataMatches', [DataProfilingController::class, 'checkDataMatches']);
    Route::get('/getUnmatchData', [DataProfilingController::class, 'getUnmatchData']);
    Route::get('/getMatchData', [DataProfilingController::class, 'getMatchData']);
    Route::get('/exportUnmatch', [DataProfilingController::class, 'exportUnmatch']);
    Route::get('/getDuplicateDataStaging', [DataProfilingController::class, 'getDuplicateDataStaging']);
    Route::get('/findDuplicates', [DataProfilingController::class, 'findDuplicates']);
    Route::get('/getAgency', [UserController::class, 'getAgency']);
    Route::get('/getRegionOffice', [UserController::class, 'getRegionOffice']);
    Route::get('/getServiceInfo', [UserController::class, 'getServiceInfo']);
    Route::get('/getDivision', [UserController::class, 'getDivision']);
    Route::get('/getUserAccount',[UserController::class,'getUserAccount']);
    

});
