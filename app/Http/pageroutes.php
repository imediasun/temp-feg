<?php 
Route::get('contact-us', 'HomeController@index');
Route::get('gmailtest', 'HomeController@gMailTest');
Route::get('gmailcallback', 'HomeController@gMailCallback')->name('gmail_callback');
Route::post('sendmail', 'HomeController@sendMail')->name('sendmail');
Route::post('save/oauth/token', 'HomeController@saveToken')->name('save_token');
Route::get('about-us', 'HomeController@index');
Route::get('service', 'HomeController@index');
Route::get('faq', 'HomeController@index');
Route::get('portfolio', 'HomeController@index');
Route::get('tickets', 'HomeController@index');
Route::get('holidays', 'HomeController@index');
Route::get('preemployment', 'HomeController@index');
Route::get('currentemployees', 'HomeController@index');
Route::get('adpsource', 'HomeController@index');
Route::get('newhires', 'HomeController@index');
Route::get('independentcontractors', 'HomeController@index');
Route::get('terminate', 'HomeController@index');
Route::get('gamemaintenance', 'HomeController@index');
Route::get('shippinggames', 'HomeController@index');
Route::get('inventor', 'HomeController@index');
Route::get('generaltips', 'HomeController@index');
Route::get('trainingmatrial', 'HomeController@index');
Route::get('popupblocker', 'HomeController@index');
?>
