<?php

use App\Http\Requests;

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

Route::get('/', function () {

  $topics =App\Topic::where('hide',0)->latest('updated_at')->get(['topic_id','topic']);
  $stopics=App\Subtopic::where('hide',0)->latest('updated_at')->get(['stopic_id','stopic','topicid']);

  $topicsperros = 3; # Topics to show per row.

  $topicrows=[];foreach($topics as $topic) {$topics2[]=$topic;}
  $topicrows=array_chunk($topics2, $topicsperros); # Group topics in threes.
  //die(var_dump($stopics));

  return view('welcome')
  ->with('topics',$topics)
  ->with('numtopics',count($topics))
  ->with('topicrows',$topicrows)
  ->with('numtopicrows',count($topicrows))
  ->with('stopics',$stopics)
  ;
});

Route::post('ajax/harticleedit/{id}', function($id) {
  $input=Request::all();
  $videos = new \App\Content;
  $videos = $videos::where('content_id',$id);
  //$videos->content = $input['txt_helptitle'];
  $videos->update(['title'=>$input['txt_helptitle'], 'content'=>$input['fedit_helpcontent']]);

  return "{$id}=||={$input['txt_helptitle']}";
});

Route::get('ajax/harticleedit/{id}', function($id) {
  $article=App\Content::where('content_id',$id)->get(['content_id','stopicid','groupid','title','content','pintonav']);
  $article=$article[0];

  $groupsall=App\Group::where('stopicid',$article->stopicid)->get(['group_id','name']);
  $groups=['None'];foreach($groupsall as $group) {$groups[$group->group_id]="{$group->name}";}

  $article2=App\Topic::where('topics.hide',0)
  ->leftjoin('subtopics','subtopics.topicid','=','topics.topic_id')
  ->orderby('topics.topic','asc','subtopics.stopic','asc')
  ->get(['subtopics.stopic_id','topics.topic','subtopics.stopic']);

  $stopics=[];foreach($article2 as $suptopic) {$stopics[$suptopic->stopic_id]="{$suptopic->topic} > {$suptopic->stopic}";}

  return view('ajax.harticleedit')->with('content', $article)->with('groups',$groups)->with('subtopics',$stopics);
});


Route::get('ajax/{id}', function($id) {
  $article=App\Content::where('content_id',$id)->get(['content_id','stopicid','created_at','updated_at','title','content']);
  return view('ajax')->with('content', $article[0]);
});


