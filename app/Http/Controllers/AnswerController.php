<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Answer;
use App\Groupe;
use App\Question;
use App\Token;
use App\Entretien_user;
use App\Entretien;
use App\Evaluation;
use App\Survey;
use Auth;

class AnswerController extends Controller
{

  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    try {
      $uid = $request->user_id;
      $eid = $request->entretien_id;
      $entretien = Entretien::find($eid);
      foreach ($request->answers as $key => $value) {
        if ($entretien->isFeedback360()) {
          $a = Answer::getMentorAnswers($key, $uid, $request->mentor_id, $eid);
        } else {
          $a = Answer::getCollAnswers($key, $uid, $eid);
        }
        if (!$a) {
          $a = new Answer();
        }

        if (isset($value['ansr']) and !is_array($value['ansr'])) {
          $ansr = $value['ansr'];
        } else {
          $ansr = json_encode($value['ansr']);
        }
        $a->question_id = $key;
        $a->user_id = isset($uid) ? $uid : '';
        if (!empty($request->mentor_id) && isset($request->is_mentor)) {
          $a->mentor_answer = $ansr;
        } else {
          $a->answer = $ansr;
          $a->user_id = isset($uid) ? $uid : '';
        }
        $a->mentor_id = $request->mentor_id;
        $a->note = isset($value['note']) ? doubleval($value['note']) : '';
        $a->entretien_id = $eid;
        $a->save();
      }
      // update entretien_user table note
      $sid = Evaluation::surveyId($eid, 1);
      $grpCount = Survey::countGroups($sid);

      $answers = Answer::where('user_id', $uid)->where('entretien_id', $eid)->get();
      if (isset($answers) && count($answers) > 0) {
        $note = 0;
        foreach ($answers as $answer) {
          $q = Question::findOrFail($answer->question_id);
          $g = Groupe::findOrFail($q->groupe->id);
          if ($g->notation_type == 'section') {
            if ($answer->question_id == $g->questions()->first()->id) $note += $answer->note;
          } else {
            $note += $answer->note;
          }
        }
      }
      $note = $grpCount > 0 ? $note / $grpCount : 0;
      if (Auth::user()->id == $uid) {
        $proporty = 'user_submitted';
      } else {
        $proporty = 'mentor_submitted';
      }
      $query = Entretien_user::where('user_id', $uid)->where('entretien_id', $eid);
      if ($entretien->isFeedback360()) {
        $query->where('mentor_id', $request->mentor_id);
      }
      $query->update([
        'note' => Answer::cutNum($note),
        $proporty => 1 // means coll or mentor start responding
      ]);
      return redirect()->back()->with('success', __("Les informations ont été sauvegardées avec succès"));
    } catch (\Exception $e) {
      return redirect()->back()->with('danger', __("Une erreur est survenue, réessayez plus tard. :error", ['error' => $e->getMessage()]));
    }

    return redirect()->back();
  }


}
