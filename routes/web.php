<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('email-verify/{link}','Auth\EmailVerification@emailVerify');

Route::get('profile-setting','Auth\settingController@index')->name('setting');
Route::get('profile-setting-email-2FA','Auth\settingController@saveTwoFaEmail')->name('saveTwoFaEmail');
Route::get('profile-setting-phone-2FA','Auth\settingController@saveTwoFaPhone')->name('saveTwoFaPhone');
Route::post('email-login-2fa','Auth\LoginController@emailCodeWithToken')->name('twoFAverificationwithtoken');
Route::post('phone-login-2fa','Auth\LoginController@phoneCodeWithToken')->name('phoneVerificationWithToken');
Route::get('phone-otp','Auth\PhoneOtpVerification@index');
Route::get('/home', 'HomeController@index')->name('home');

//SSLCOMMERZ Start
Route::get('/pay/ssl', 'PublicSslCommerzPaymentController@index')->name('addmoney.paywithssl');
Route::POST('/success', 'PublicSslCommerzPaymentController@success');
Route::POST('/fail', 'PublicSslCommerzPaymentController@fail');
Route::POST('/cancel', 'PublicSslCommerzPaymentController@cancel');
Route::POST('/ipn', 'PublicSslCommerzPaymentController@ipn');
//SSLCOMMERZ END

//Paypal start
Route::get('pay/paypal/','PaypalController@payWithPaypal')->name('addmoney.paywithpaypal');
Route::get('pay/paypal/success','PaypalController@payWithPaypalsiccess')->name('paypal.success');;
//Route::get('ipn/notify','PaypalController@postNotify');
//End paypal

//Stripe start
//Route::get('addmoney/stripe','StripeController@payWithStripe')->name('addmoney.paywithstripe');
//Route::get('addmoney/stripe','StripeController@postPaymentWithStripe')->name('addmoney.stripe');
Route::get('addmoney/stripe', array('as' => 'addmoney.paywithstripe','uses' => 'StripeController@payWithStripe',));
Route::post('addmoney/stripe', array('as' => 'addmoney.stripe','uses' => 'StripeController@postPaymentWithStripe',));
//Stripe end

Route::get("chat","ChatController@index")->name("chat");
Route::get("chat/inbox/{id}/{name}","ChatController@userInbox")->name("live.chat");
Route::post("chat/inbox","ChatController@saveMsg")->name("live.chat.send");
Route::get("chat/showMsg/{id}","ChatController@showMsg")->name("live.chat.data");