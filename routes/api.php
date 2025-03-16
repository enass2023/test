<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

   Route::middleware('auth:api')->group(function () {
  
    Route::post('/logout',[App\Http\Controllers\AuthController::class,'logout']);

     Route::prefix('tasks/')->controller(App\Http\Controllers\TaskController::class)->group(function(){


        Route::post('', 'store');

        Route::get('', 'index');

        Route::get('/{id}', 'indexByid');
  
        Route::PUT('/{id}', 'update');
        
        Route::DELETE('/{id}', 'destroy');
        
        });

        Route::post('/task_search', [App\Http\Controllers\TaskController::class, 'search']);

        Route::get('/show_all_tasks', [App\Http\Controllers\AuthController::class,'getTasks']);
   

        
      });
   

            //..........................Auth..........

       Route::post('/register',[App\Http\Controllers\AuthController::class,'register']);
       Route::post('/login',[App\Http\Controllers\AuthController::class,'login']);








      
    


     

     

