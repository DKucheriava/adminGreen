<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\SupportPaymentController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/getRegistration', [ApiController::class,'getRegistration']);
Route::any('/user-email-verification/{token}', [ApiController::class,'userEmailVerification']);
Route::post('/user-registration', [ApiController::class,'userRegistration']);
Route::post('/update_session', [ApiController::class,'update_session']);
Route::post('/update_sessiononpause', [ApiController::class,'update_sessiononpause']);
Route::post('/update_intraction', [ApiController::class,'update_intraction']);
Route::post('/get_sessiononpause', [ApiController::class,'get_sessiononpause']);
Route::post('/update_sessionoitems', [ApiController::class,'update_sessionoitems']);
Route::any('/user-login', [ApiController::class,'userLogin']);
Route::post('/update_fcmtoken', [ApiController::class,'updatefcmtoken']);
Route::post('/updateviewdatainter', [ApiController::class,'updateviewdatainter']);
Route::post('/updateviewdatainterend', [ApiController::class,'updateviewdatainterend']);
Route::post('/update_sessionapp', [ApiController::class,'update_sessionapp']);
Route::post('/sendpoemonfcm', [ApiController::class,'sendpoemonfcm']);
Route::post('/forgot-password', [ApiController::class,'forgotPassword']);
Route::post('/reset-password', [ApiController::class,'resetPassword']);
Route::post('/contact-us', [ApiController::class,'contactUs']);
Route::post('/sendbothpoemonfcm', [ApiController::class,'sendbothpoemonfcm']);
Route::post('/faq-list', [ApiController::class,'getFaqList']);
Route::post('/term-condition', [ApiController::class,'getTermCondtion']);
Route::post('/privacy-policy', [ApiController::class,'getPrivacyPolicy']);
Route::post('/get-about-us', [ApiController::class,'getAboutUsData']);
Route::post('/get-session', [ApiController::class,'getSessionData']);
Route::post('/get-profile', [ApiController::class,'getProfileData']);
Route::post('/getpaypl_plan', [ApiController::class,'getpaypl_plan']);
Route::post('/update-profile/{id}', [ApiController::class,'updateProfileData']);
Route::post('/update-password/{id}', [ApiController::class,'updatePassword']);
Route::post('/get-poem-mood-list', [ApiController::class,'getPoemMoodListData']);
Route::post('/get-poem-theme-list', [ApiController::class,'getPoemThemeListData']);
Route::post('/get-poem-list', [ApiController::class,'getPoemList']);
Route::post('/load-more-poem', [ApiController::class,'load_more_poem']);
Route::post('/get-all-poem', [ApiController::class,'getAllPoemList']);
Route::post('/get-all-country', [ApiController::class,'getAllCountry']);
Route::post('/get-all-creator', [ApiController::class,'getAllCreatorList']);
Route::post('/poem/{poemId}', [ApiController::class,'getPoemDetail']);
Route::post('/deletePoem/{poemId}', [ApiController::class,'deletePoem']);
Route::post('/removePoem/{poemId}', [ApiController::class,'removePoem']);
Route::post('/add-poem', [ApiController::class,'addPoem']);
Route::post('/send-me-poem-email', [ApiController::class,'sendMePoemEmail']);
Route::post('/recommend-poem', [ApiController::class,'recommendPoem']);
Route::post('/add-to-collection', [ApiController::class,'addToCollection']);
Route::post('/remove-from-collection', [ApiController::class,'removeFromCollection']);
Route::post('/send-welcomemail', [ApiController::class,'sendwelcomemail']);
Route::post('/send-me-a-poem', [ApiController::class,'sendMePoem']);
Route::post('/delete-account', [ApiController::class,'deleteaccount']);
Route::post('/send-reasonmail', [ApiController::class,'sendreasonmail']);
Route::post('/unsubscribe-email-verification', [ApiController::class,'unsubscribeemailverification']);
Route::post('/payment-paypal', [SupportPaymentController::class,'paymentPaypal']);
Route::post('/payment-stripe', [SupportPaymentController::class,'stripePost']);
Route::post('/payment-paypalmonth', [SupportPaymentController::class,'paymentPaypalmonth']);















