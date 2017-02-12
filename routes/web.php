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

  //$topics = \App\Topic::all();
  $topics=DB::table('topics')->where('hide',0)->orderby('updated_at','desc')->get();

  $stopics=DB::table('stopics')->where('hide',0)
  ->orderby('updated_at','desc')->get();

  // Need a counter for the table columns:
  //$i             = 0;
  //$topic_counter = 1;
    
  $items         = 3; # Topics to show per row.

  $topics2=[];foreach($topics as $topic) {$topics2[]=$topic;}

  $topics2=array_chunk($topics2, $items); # Group topics in threes.
  //die(var_dump($stopics));

  return view('welcome')
  ->with('topics2',$topics)
  ->with('topics',$topics2)
  ->with('numtopics2',count($topics2))
  ->with('numtopics',count($topics))
  ->with('stopics',$stopics)
  ;
});
