<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectsMenu extends Model
{
  public static function display() {
    $list = \File::directories('../..');
    echo '<ul id="projectsmenu">';
    foreach($list as $project){
      $projectname = ucwords(str_replace('../../', '', $project));
      if ($projectname!='Shared') echo '<li><a href="/'.strtolower($projectname).'/public/">' . $projectname . '</a></li>';
    }
    echo '</ul>';
  }
}
