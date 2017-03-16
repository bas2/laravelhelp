<?php

//use App\Http\Requests;

Route::get('home', 'HelpController@index');

// Complete article update:
Route::post('article/edit/{id}', 'HelpController@postArticleEdit');

// Display article edit form.
Route::get('article/edit/{id}', 'HelpController@getArticleEdit');

// Get subtopic articles:
Route::get('article/subtopic/{id}', 'HelpController@articles' );

// Get article contents.
Route::get('article/{id}', 'HelpController@article');

// Add new reply:
Route::get('article/reply/{id}', 'HelpController@replyToArticle');

// Delete article
Route::get('article/delete/{id}', 'HelpController@deleteArticle');

// New article
Route::get('article/new/subtopic/{id}', 'HelpController@addArticle' );


// New subtopic.
Route::get('subtopic/new/{id}', 'HelpController@getAddSubtopic');

// New subtopic:
Route::post('subtopic/new/{id}', 'HelpController@addSubtopic');

// Rename or delete subtopic page:
Route::get('subtopic/{id}', 'HelpController@getSubtopicActions');

// Rename or delete subtopic complete:
Route::post('subtopic/{id}', 'HelpController@subtopicActions');


// Add group.
Route::post('group/add/{id}', 'HelpController@addGroup');

// Remove group.
Route::post('group/remove/{id}', 'HelpController@removeGroup');
