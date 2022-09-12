<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\PoemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

	Route::get('/clear', function() {
		$exitCode = Artisan::call('cache:clear');
			echo '<h1>Cache facade value cleared</h1>';
		$exitCode = Artisan::call('route:clear');
			echo '<h1>Route cache cleared</h1>';
		$exitCode = Artisan::call('view:clear');
			echo '<h1>View cache cleared</h1>';
		$exitCode = Artisan::call('config:cache');
			return '<h1>Clear Config cleared</h1>';
	});

	Route::any('/', [AdminController::class,'loginAdmin']);
	
	Route::any('/admin/login', [AdminController::class, 'loginAdmin']);
	Route::any('/login', [AdminController::class, 'loginAdmin']);
	Route::any('/forgot-password', [AdminController::class, 'forgotPassword']);
	Route::any('/change-password', [AdminController::class, 'changePassword']);
	Route::any('/set-password/{security_code}/{user_id}', [AdminController::class,'set_password']);

	Route::group(['prefix'=>'admin','middleware'=>'CheckAdminLogin'],function(){
		Route::match(['get','post'],'/dashboard',[AdminController::class, 'dashboard']);
		Route::any('/profile', [AdminController::class, 'admin_profile']);
		Route::any('/changePassword', [AdminController::class, 'changePasswordAdmin']);
		Route::any('/logout', [AdminController::class, 'adminLogout']);

		Route::any('/user/list', [UserController::class, 'getUserList']);
		Route::any('/user/add', [UserController::class, 'addUser']);
		Route::any('/user/edit/{id}', [UserController::class, 'editAdminUser']);
		Route::any('/user/delete/{id}', [UserController::class, 'deleteAdminUser']);
        
        // PoemController
		Route::get('/poem/changeStatus', [PoemController::class, 'changeStatusPoem']);
		
		// PoemController
		Route::any('/poem/list', [PoemController::class, 'getPoemList']);
		Route::any('/poem/add', [PoemController::class, 'addPoem']);
		Route::any('/poem/edit/{id}', [PoemController::class, 'editPoem']);
        Route::any('/poem/delete/{id}', [PoemController::class, 'deletePoem']);
        Route::any('/check-poet', [PoemController::class, 'validatePoetName']);
		Route::any('/edit-check-poet', [PoemController::class, 'validateEditPoetName']);
		
        // privacy term & condtion    
        Route::any('/edit-privacy-policy', [HomeController::class, 'privacyPolicy']);
		Route::any('/edit-terms-condtion', [HomeController::class, 'term']);
        
        // Faq
		Route::any('/add-faq',[FaqController::class,'addFaq']);
		Route::any('/edit-faq/{id}',[FaqController::class,'editFaq']);
		Route::any('/check-faq-title', [FaqController::class, 'validateFaqTitle']);
		Route::any('/edit-check-faq-title', [FaqController::class, 'validateEditFaqTitle']);
		Route::get('/faqs', [FaqController::class, 'indexFaq']);
		Route::post('delete-faq/{id}', [FaqController::class, 'deleteFaq']);
		Route::any('/contact-us', [AdminController::class, 'contactUsQueryList']);
		
	});
