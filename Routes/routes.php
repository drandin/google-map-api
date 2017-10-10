<?php

Route::group(['prefix' => 'map/'], function() {
    Route::get('/', 'APIGoogleMapController@map');
    Route::get('/markers', 'APIGoogleMapController@markers');
    Route::get('/people', 'APIGoogleMapController@people');
});
