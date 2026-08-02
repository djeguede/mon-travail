<?php

use App\Http\Controllers\JobController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->get('/user', function(){
    return $request->user();
});

//Job Routes
Route::middleware('api')->group(function(){
    Route::get('search', [JobController::class, 'search'])->name('job.search');

    //Pages api
    Route::get('company-categories',[JobController::class, 'getCategories'])->name('job.getCategories');
    Route::get('job-titles',[JobController::class, 'getAllbyTitle'])->name('job.getAllByTitile');
    Route::get('companies',[JobController::class, 'getAllOrganization'])->name('job.getAllOrganization');
});