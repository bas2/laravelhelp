<?php

use App\Http\Requests;

Route::get('home', 'HelpController@index');

// Complete article update:
Route::post('ajax/harticleedit/{id}', 'HelpController@postArticleEdit');

// Display article edit form.
Route::get('ajax/harticleedit/{id}', 'HelpController@getArticleEdit');

// Get subtopic articles:
Route::get('ajax/content/{id}', 'HelpController@articles' );

// Get article contents.
Route::get('ajax/{id}', 'HelpController@article');

// Add new reply:
Route::get('ajax/hreplytoarticle/{id}', 'HelpController@replyToArticle');

// Delete article
Route::get('ajax/hdelarticle/{id}', 'HelpController@deleteArticle');

// New article
Route::get('ajax/hnewarticle/{id}', 'HelpController@addArticle' );


// New subtopic.
Route::get('stopic/new/{id}', 'HelpController@getAddSubtopic');

// New subtopic:
Route::post('stopic/new/{id}', 'HelpController@addSubtopic');

// Rename or delete subtopic page:
Route::get('subtopic/{id}', 'HelpController@getSubtopicActions');

// Rename or delete subtopic complete:
Route::post('subtopic/{id}', 'HelpController@subtopicActions');


// Add group.
Route::post('ajax/groups/add/{id}', 'HelpController@addGroup');

// Remove group.
Route::post('ajax/groups/remove/{id}', 'HelpController@removeGroup');
