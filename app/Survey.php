<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
  public function groupes()
  {
    return $this->hasMany('App\Groupe');
  }

  // todo :: review this relation a
  public function evaluations()
  {
    return $this->belongsToMany('App\Evaluation');
  }

  public function evaluation()
  {
    return $this->belongsTo('App\Evaluation');
  }

  public static function icompleteSurvey($sid)
  {
    $survey = Survey::findOrFail($sid);
    $groupes = $survey->groupes;
    $incompleteSurvey = false;
    foreach ($groupes as $groupe) {
      foreach ($groupe->questions as $question) {
        if ($question->type == 'checkbox' || $question->type == 'radio') {
          if (count($question->children) <= 0) {
            $incompleteSurvey = true;
          } else {
            $incompleteSurvey = false;
          }
        }
      }
    }
    return $incompleteSurvey;
  }

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public static function getAll()
  {
    $user = \Auth::user();
    if(!empty($user->society_id)){ // this user is not owner
      $surveys = $user->owner->getSurveys();
    } else {
      $surveys = $user->getSurveys();
    }
    return $surveys;
  }

  public static function countGroups($sid)
  {
    $survey = Survey::findOrFail($sid);
    $c = 0;
    foreach ($survey->groupes()->get() as $group) {
      if($group->questions()->count() > 0 && $group->notation_type == 'section'){
        $c ++;
      } elseif($group->questions()->count() > 0 && $group->notation_type == 'item') {
        $c += $group->questions()->count();
      }
    }
    return $c;
  }

  public function getModele() {
    return Modele::find($this->model_id);
  }

}
