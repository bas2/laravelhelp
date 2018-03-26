<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use \App;

use \Carbon\Carbon;


class HelpController extends Controller
{

    /*
     * Display main application screen.
     * GET: /.
     */ 
    public function index()
    {
        $dirpath = '../..';
        $proj    = [];
        foreach(\File::directories($dirpath) as $project)
        {
            $prj = str_replace($dirpath . '/', '', $project);
            if (substr($prj, 0, 1) != '_' && !in_array($prj, ['logs']))
            {
                $proj[] = ucwords($prj);
            }
        }
        sort($proj);

        $topics2 = [];
        $topics  = App\Topic::where('hide', 0)->latest('updated_at')->get(['topic_id', 'topic']);
        $topicrows = [];
        foreach ($topics as $topic)
        {
            $topics2[] = $topic;
        }

        return view('welcome')
        ->with('topics', $topics)
        ->with('projlist', $proj)
        ;
    }


    /*
     * Complete article update with articleid.
     * POST: article/edit/{id}.
     */ 
    public function postArticleEdit($articleid)
    {
        $input   = \Request::all();
        $article = new App\Content;
        $article = $article::where('content_id', $articleid);
        $article_body = (!empty($input['fedit_helpcontent'])) ? $input['fedit_helpcontent'] : '' ;
        $article->update(['title' => $input['txt_helptitle'], 'content' => $article_body, 'groupid' => $input['groupid'], 'stopicid' => $input['stopicid']]);

        // Update update_at for topic:
        $content = App\Content::getContentrow($articleid, ['stopicid', 'parentid']);
        if ($content[0]->parentid > 0)
        {
            // Also set updated_at for the main article for this reply.
            $mainarticle = App\Content::where('content_id', $content[0]->parentid)->update(['updated_at' => Carbon::now()]);
        }
        $subtopic = App\Subtopic::getTopicrow($content[0]->stopicid, ['topicid']);
        $topic    = App\Topic::where('topic_id', $subtopic[0]->topicid)->update(['updated_at' => Carbon::now()]);
        $subtopic = App\Subtopic::where('stopic_id', $content[0]->stopicid)->update(['updated_at' => Carbon::now()]);
        return "{$articleid}=||={$input['txt_helptitle']}";
    }


    /*
     * Edit article with id id.
     * GET: article/edit/{id}.
     */
    public function getArticleEdit($articleid)
    {
        $article = App\Content::where('content_id', $articleid)->get(['content_id', 'stopicid', 'groupid', 'title', 'content', 'pintonav']);
        $article = $article[0];

        $groupsall = App\Group::where('stopicid',$article->stopicid)->orderBy('name')->get(['group_id', 'name']);
        $groups = ['--None--'];
        foreach ($groupsall as $group)
        {
            $groups[$group->group_id] = "{$group->name}";
        }

        $article2 = App\Topic::where('topics.hide', 0)
        ->leftjoin('subtopics', 'subtopics.topicid', '=', 'topics.topic_id')
        ->orderby('topics.topic', 'asc', 'subtopics.stopic', 'asc')
        ->get(['subtopics.stopic_id', 'topics.topic', 'subtopics.stopic']);

        $stopics = [];
        foreach ($article2 as $suptopic)
        {
            $stopics[$suptopic->stopic_id] = "{$suptopic->topic} > {$suptopic->stopic}";
        }

        return view('ajax.harticleedit')->with('content', $article)->with('groups', $groups)->with('subtopics',$stopics);
    }


    /*
     * Get subtopics with id id.
     * GET: article/subtopic/{id}.
     */
    public function articles($subtopicid)
    {
        $input     = \Request::all();
        $subtopic1 = new App\Subtopic;
        $subtopic1->stopic_id = $subtopicid;
        return view('ajax.content')
        ->with('stopic', $subtopic1)
        ->with('orderby', str_replace(' hilite', '', $input['orderby']) )
        ->with('filtergroup', $input['group'] )
        ;
    }


    /*
     * Display article with id id.
     * GET: article/{id}.
     */
    public function article($articleid)
    {
        $article = App\Content::where('content_id', $articleid)
        ->get(['content_id', 'stopicid', 'created_at', 'updated_at', 'title', 'content'])
        ->first();

        return view('ajax')->with('content', $article);
    }


    /*
     * Display article reply with id id.
     * GET: article/reply/{id}.
     */ 
    public function replyToArticle($articleid)
    {
        $subtopic = App\Content::where('content_id', $articleid)->get(['stopicid']);

        $create = new App\Content;
        $create->stopicid = $subtopic[0]->stopicid;
        $create->parentid = $articleid;
        $create->content = '';
        $create->controllerid = 4;
        $create->save();

        return ($create->save()) ? "{$create->id}|Bashir|" . date('l jS M Y, H:i') : 'No' ;
    }


    /*
     * Delete article with id id.
     * GET: article/delete/{id}.
     */ 
    public function deleteArticle($articleid)
    {
        $article = App\Content::where('content_id', $articleid)->delete();
    }


    /*
     * Add an article on a sub topic.
     * GET: article/new/subtopic/{id}.
     */ 
    public function addArticle($subtopicid)
    {
        $create = new App\Content;
        $create->stopicid = $subtopicid;
        $create->content = '';
        $create->controllerid = 4;
        $create->save();

        return ($create->save()) ? "{$create->id}|Bashir|" . date('l jS M Y, H:i') : 'No' ;
    }


    /*
     * Show add sub topic form.
     * GET: subtopic/new/{id}.
     */ 
    public function getAddSubtopic()
    {
        return view('subtopicnew');
    }


    /*
     * Actually create the sub topic.
     * POST: subtopic/new/{id}.
     */ 
    public function addSubtopic($topicid)
    {
        $input = \Request::all();
        $create = new App\Subtopic;
        $create->topicid = $topicid;
        $create->stopic = $input['subtopic'];
        $create->save();

        return redirect('home');
    }


    /*
     * 
     * GET: subtopic/{id}.
     */ 
    public function getSubtopicActions($subtopicid)
    {
        $subtopic = App\Subtopic::where('stopic_id', $subtopicid)->get(['stopic']);
        return view('subtopicrename')->with('subtopic', $subtopic[0]);
    }


    /*
     * Complete delete / update of subtopic with id id.
     * POST: subtopic/{id}.
     */ 
    public function subtopicActions($subtopicid)
    {
        $action   = (!empty(request('d'))) ? 'd' : 'u';
        
        $subtopic = new App\Subtopic;
        $subtopic = $subtopic::where('stopic_id', $subtopicid);

        if ($action == 'd')
        {
            $subtopic->delete();
        }
        else
        {
            $subtopic->update([
                'stopic' => request('subtopic')
            ]);
        }

        return redirect('home');
    }


    /*
     * Add the group.
     * POST: group/add/{id}.
     */ 
    public function addGroup($subtopicid)
    {
        $input  = \Request::all();
        $create = new App\Group;
        $create->name = $input['name'];
        $create->stopicid = $subtopicid;
        $create->save();

        $groupsall = App\Group::where('stopicid', $subtopicid)->orderBy('name')->get(['group_id', 'name']);
        $groups = ['--None--'];
        foreach($groupsall as $group)
        {
            $groups[$group->group_id] = "{$group->name}";
        }

        return view('ajax.groups')->with('groups', $groups)->with('groupid', $create->id);
    }


    /*
     * Remove group with id.
     * POST: group/remove/{id}.
     */
    public function removeGroup($groupid)
    {
        $input = Request::all();
        $delete = new App\Group;
        $delete->where('group_id', $groupid)->delete();

        $groupsall = App\Group::where('stopicid', $input['stopicid'])->orderBy('name')->get(['group_id', 'name']);
        $groups = ['--None--'];
        foreach($groupsall as $group)
        {
            $groups[$group->group_id] = "{$group->name}";
        }

        return view('ajax.groups')->with('groups', $groups)->with('groupid', $groupid);
    }


    /*
     * Show add topic form.
     * GET: topic/new.
     */
    public function getTopicActions()
    {
        //$subtopic=App\Topic::where('stopic_id',1)->get(['stopic']);
        return view('topicnew')->with('topic', '');
    }


    /*
     * Add the new topic.
     * POST: topic/new.
     */
    public function postTopicActions(Request $request)
    {
        $this->validate(request(), ['topic' => 'required|min:3|max:300']);
        $createtopic = new \App\Topic;
        $createtopic->topic = $request['topic'];
        $createtopic->save();
        session()->flash('message', "Topic {$request['topic']} was created");
        return redirect('HomeScreen');
    }


    /*
     * Return current time.
     * GET: time
     */
    public function getTime()
    {
        return date('H:i');
    }


}
