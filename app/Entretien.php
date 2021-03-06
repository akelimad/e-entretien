<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;

class Entretien extends Model
{
  const ENABLED_STATUS = "Actif";
  const DISABLED_STATUS = "Inactif";

  const CURRENT_STATUS = "En cours";
  const FINISHED_STATUS = "Fini";
  const EXPIRED_STATUS = "Expiré";

  public static function answered($eid, $uid)
  {
    $eu = Entretien_user::where('entretien_id', $eid)->where('user_id', $uid)
      ->where('user_submitted', 2)
      ->first();
    return $eu ? $eu : false;
  }

  public static function answeredMentor($eid, $uid, $mid)
  {
    $count = Entretien_user::where('entretien_id', $eid)
      ->where('user_id', $uid)
      ->where('mentor_id', $mid)
      ->where('mentor_submitted', 2)
      ->count();
    return $count > 0;
  }

  public function users()
  {
    return $this->belongsToMany('App\User');
  }

  public function evaluations()
  {
    return $this->belongsToMany('App\Evaluation');
  }

  public function skills()
  {
    return $this->hasMany('App\Skill');
  }

  public function salaries()
  {
    return $this->hasMany('App\Salary');
  }

  public function objectifs()
  {
    return $this->hasMany('App\Objectif');
  }

  public function formations()
  {
    return $this->hasMany('App\Formation');
  }

  public function documents()
  {
    return $this->hasMany('App\Document');
  }

  public function remunerations()
  {
    return $this->hasMany('App\Remuneration');
  }

  public function decisions()
  {
    return $this->hasMany('App\Decision');
  }

  public function comments()
  {
    return $this->hasMany('App\Comment');
  }

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public static function getAll()
  {
    $user = \Auth::user();
    if (!empty($user->society_id)) { // this user is not owner
      $entretiens = $user->owner->getEntretiens();
    } else {
      $entretiens = $user->getEntretiens();
    }
    return $entretiens;
  }

  public static function existInterview($eid, $user_id, $start, $end, $operator = "<>")
  {
    if (\Request::is('entretiens/storeCheckedUsers')) {
      $operator = "=";
    }
    $existInterview = \DB::table('entretiens as e')
      ->join('entretien_user as eu', 'e.id', '=', 'eu.entretien_id')
      ->select('e.*', 'e.id as entretienId', 'eu.*')
      ->where('eu.user_id', $user_id)
      ->where('e.id', $operator, $eid)
      ->where(function ($query) use ($start, $end) {
        $query->where(function ($q) use ($start, $end) {
          $q->where('e.date', '>', $start)
            ->where('e.date', '<', $end);
        })->orWhere(function ($q) use ($start, $end) {
          $q->where('e.date', '<', $start)
            ->where('e.date_limit', '>', $end);
        })->orWhere(function ($q) use ($start, $end) {
          $q->where('e.date_limit', '>', $start)
            ->where('e.date_limit', '<', $end);
        })->orWhere(function ($q) use ($start, $end) {
          $q->where('e.date', '>', $start)
            ->where('e.date_limit', '<', $end);
        });
      })->count();
    return $existInterview;
  }

  public static function note($eid, $uid)
  {
    $eu = Entretien_user::where('entretien_id', $eid)->where('user_id', $uid)->first();
    return $eu ? $eu->note : 0;
  }

  public static function countUnanswered()
  {
    $society = User::getOwner();
    $countUnanswred = \DB::table('entretiens as e')
      ->join('entretien_user as eu', 'e.id', '=', 'eu.entretien_id')
      ->select('e.id')
      ->where('e.user_id', $society->id)
      ->where('eu.user_submitted', 1)
      ->where('eu.mentor_submitted', 0)
      ->get();
    return count($countUnanswred);
  }

  public static function findEvaluations($entretien)
  {
    $evaluations = \DB::select("SELECT * FROM evaluations eval join entretien_evaluation ev on eval.id = ev.evaluation_id and ev.entretien_id = " . $entretien->id . " order by eval.sort_order");

    return $evaluations;
  }

  public static function notationBySectionOrItem($entretien_id)
  {
    $entretienEvalSurveyId = Evaluation::surveyId($entretien_id, 1);
    $survey = Survey::find($entretienEvalSurveyId);
    if (!$survey) return false;
    $c = 0;
    foreach ($survey->groupes as $group) {
      if (!empty($group->notation_type)) {
        $c += 1;
      }
    }

    return $c >= 1;
  }

  public function getStatus() {
    $status = $this->status;
    if ($this::isExpired()) {
      return $this::EXPIRED_STATUS;
    }
    if (Entretien_user::isFinished($this)) {
      return $this::FINISHED_STATUS;
    }

    return $status;
  }

  public function isCurrent() {
    return $this->status == $this::CURRENT_STATUS;
  }

  public function isEnabled() {
    return $this->enabled == 1;
  }

  public function isDisabled() {
    return $this->enabled == 0;
  }

  public function isExpired() {
    return date('Y-m-d H:i', strtotime('now')) > $this->date_limit || $this->status == $this::EXPIRED_STATUS;
  }

  public function isFinished() {
    return $this->status = $this::FINISHED_STATUS;
  }

  public function getOptions() {
    return json_decode($this->options, true) ?: [];
  }

  public function isAnnualInterview() {
    return $this->model == "Entretien annuel";
  }

  public function isFeedback360() {
    $entretienModel = Entretien::select('models.ref')
      ->join('models', 'entretiens.model_id', 'models.id')
      ->where('entretiens.id', $this->id)
      ->first();
    return $entretienModel && $entretienModel->ref == 'FB360';
  }

  public function canBeFilledByUser($user_id) {
    $user = User::find($user_id);
    $isMentor = Auth::user() != $user;
    $isColl = Auth::user() == $user;
    $campaign = Campaign::where('entretien_id', $this->id)->first();

    if (
      $this->isDisabled() || // is desabled
      !$this->isFeedback360() && $isMentor && date('Y-m-d') > date('Y-m-d', strtotime($this->date_limit)) || // already expired for manager
      !$this->isFeedback360() && $isColl && date('Y-m-d') > date('Y-m-d', strtotime($this->date)) || // already expired for collaborator
      $this->isFeedback360() && date('Y-m-d', strtotime($this->date_limit)) < date('Y-m-d') || // expired for evaluator
      $campaign && $campaign->shedule_type == "sheduled" && date('Y-m-d H:i', strtotime($campaign->sheduled_at)) > date('Y-m-d H:i') // not yet started
    ) {
      return false;
    }

    return true;
  }

  public static function canBeFilledByUserMessage() {
    return __("Désolé, vous avez dépassé la date limite, ou la campagne est désactivée, ou pas encore lancée");
  }

  public function getStartDate() {
    $campaign = Campaign::where('entretien_id', $this->id)->first();
    if (!$campaign) return '---';
    if ($campaign->shedule_type == "sheduled") {
      return date('d/m/Y', strtotime($campaign->sheduled_at));
    } else {
      return date('d/m/Y', strtotime($campaign->created_at));
    }
  }

  public function getCronTabExpression() {
    if ($this->freq_reminder == 'every_day') {
      return '0 1 * * *';
    } elseif ($this->freq_reminder == 'every_2days') {
      return '0 1 */2 * *';
    } else if ($this->freq_reminder == 'every_3days') {
      return '0 1 */3 * *';
    } else if ($this->freq_reminder == 'every_week') {
      return '0 1 * * 1'; // on monday 1 1sr day of week at 1h:00
    } else if ($this->freq_reminder == 'every_2weeks') {
      return '0 1 1,15 * *'; // 1,15 = 1st day, middle of month at 1h:00
    } else if ($this->freq_reminder == 'every_month') {
      return '0 1 1 * *'; // 1st day of month at 1h:00
    } else {
      return '0 1 * * *';
    }
  }

  public function getUsersEvaluators() {
    $ids = [];
    if ($this->isFeedback360()) {
      $ids = Entretien_user::where('entretien_id', $this->id)->pluck('mentor_id')->toArray();
    } else {
      foreach ($this->users as $user) {
        $ids[] = $user->id;
      }
    }

    return $ids;
  }

  public function getUsersIdToEvaluate() {
    $ids = Entretien_user::where('entretien_id', $this->id)->pluck('user_id')->toArray();
    if ($this->isFeedback360()) {
      $ids = isset($ids[0]) ? $ids[0] : [];
    }

    return $ids;
  }


}
