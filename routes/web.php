<?php

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

  $topics=DB::table('topics')->where('hide',0)->latest('updated_at')->get(['topic_id','topic']);
  $stopics=DB::table('stopics')->where('hide',0)->latest('updated_at')->get(['stopic_id','stopic','topicid']);

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
