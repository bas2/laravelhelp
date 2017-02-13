<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    public static function getContent($subtopicid) {
      return static::where('contents.stopicid',$subtopicid)
      ->leftJoin('groups','contents.groupid','=','groups.group_id')
      ->where('parentid', 0)
      ->latest('contents.updated_at')
      ->get(['groups.name', 'contents.title', 'content_id']);
    }

    public static function getReplies($mainarticleid) {
      return static::where('contents.parentid',$mainarticleid)
      ->latest('contents.updated_at')
      ->get(['content_id','contents.title']);
    }
}
