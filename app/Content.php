<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    public static function getContent($subtopicid, $orderby='moddate', $filtergroup=0) {
      $orderby2 = ($orderby=='oname') ? 'concat_ws(" ",groups.name,contents.title)' 
      : 'contents.updated_at desc' ;
      if ($orderby == 'ofree') { $orderby2 = 'contents.free_order'; }

      if($filtergroup==0) {
        return static::where('contents.stopicid',$subtopicid)
        ->leftJoin('groups','contents.groupid','=','groups.group_id')
        ->where('parentid', 0)
        ->orderByRaw($orderby2)
        ->get(['groups.name', 'contents.title', 'content_id']);
      }
      else {
        return static::where('contents.stopicid',$subtopicid)
        ->leftJoin('groups','contents.groupid','=','groups.group_id')
        ->where('parentid', 0)
        ->where('groupid', $filtergroup)
        ->orderByRaw($orderby2)
        ->get(['groups.name', 'contents.title', 'content_id']);
      }
    }

    public static function getReplies($mainarticleid) {
      return static::where('contents.parentid',$mainarticleid)
      ->latest('contents.updated_at')
      ->get(['content_id','contents.title']);
    }

    public static function getContentrow($id, $columns=['*']) {
      return static::where('content_id',$id)
      ->get($columns);
    }
}
