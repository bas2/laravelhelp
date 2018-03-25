<?php
Route::get('/', 'HelpController@index')->name('HomeScreen');

Route::post('article/edit/{id}','HelpController@postArticleEdit'); #Complete article update
Route::get('article/edit/{id}', 'HelpController@getArticleEdit'); #Display article edit form
Route::get('article/subtopic/{id}', 'HelpController@articles' ); #Get subtopic articles
Route::get('article/{id}',          'HelpController@article'); #Get article contents
Route::get('article/reply/{id}',    'HelpController@replyToArticle'); #Add new reply
Route::get('article/delete/{id}',   'HelpController@deleteArticle'); #Delete article
Route::get('article/new/subtopic/{id}', 'HelpController@addArticle'); #New article

Route::get( 'subtopic/new/{id}', 'HelpController@getAddSubtopic'); #New subtopic
Route::post('subtopic/new/{id}', 'HelpController@addSubtopic');   #New subtopic
Route::get('subtopic/{id}', 'HelpController@getSubtopicActions'); #Rename or delete subtopic page
Route::post('subtopic/{id}', 'HelpController@subtopicActions'); #Rename or delete subtopic complete

Route::get('topic/new', 'HelpController@getTopicActions');
Route::post('topic/new', 'HelpController@postTopicActions');

Route::post('group/add/{id}', 'HelpController@addGroup');       #Add group.
Route::post('group/remove/{id}', 'HelpController@removeGroup'); #Remove group.

Route::get('time', 'HelpController@getTime');
