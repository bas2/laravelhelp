<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subtopic extends Model
{
    /**
     * Return all subtopics from topic.
     *
     * @param  integer|null  $topicid
     * @return ?
     */
    public static function getTopicSubtopics($topicid) {
      return static::where('topicid',$topicid)->where('hide',0)->latest('updated_at')->get(['stopic_id','stopic']);
    }

    public static function getTopicrow($subtopicid, $columns=['*']) {
      return static::where('stopic_id',$subtopicid)->get($columns);
    }
}
