<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Groupe;

class Answer extends Model
{
    const NOTE_DEGREE = [
        1 => ['ref' => 'I', 'title' => "Insuffisant"],
        2 => ['ref' => 'ED', 'title' => "En dessous des attentes"],
        3 => ['ref' => 'EL', 'title' => "En ligne avec les attentes"],
        4 => ['ref' => 'AD', 'title' => "Au-dessus des attentes"],
        5 => ['ref' => 'R', 'title' => "Remarquable"],
    ]; 

    public function question()
    {
        return $this->belongsTo('App\Question');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public static function getCollAnswers($qid, $uid, $eid)
    {
        $answer = Answer::where('question_id', $qid)
            ->where('user_id', $uid)
            ->where('entretien_id', $eid)
            ->first();
        if($answer && ($answer->answer != "" || $answer->mentor_answer != "")){
            return $answer;
        }
        return false;
    }

    public static function getMentorAnswers($qid, $uid, $evaluator_id = 0, $eid)
    {
        $user = User::findOrFail($uid);
        $answer = Answer::where('question_id', $qid)
            ->where('user_id', $user->id)
            ->where('mentor_id', $evaluator_id)
            ->where('entretien_id', $eid)
            ->first();
        if($answer && $answer->mentor_answer != null){
            return $answer;
        }
        return false;
    }

    public static function getGrpNote($gid, $uid, $eid)
    {
        $group = Groupe::find($gid);
        $user = User::find($uid);
        $sum = 0;
        if($group->ponderation > 0) {
            $grpQstsId = $group->questions->pluck('id')->toArray();
            $answers = Answer::whereIn('question_id', $grpQstsId)
              ->where('user_id', $user->id)
              ->where('mentor_id', $user->parent->id)
              ->where('entretien_id', $eid)
              ->get();
            if (empty($answers)) return 0;
            $sum = 0;
            foreach($answers as $answer) {
                $question = Question::find($answer->question_id);
                $sum += $answer->note * ($question->ponderation / 100);
            }
        }
        $sum = number_format($sum, '2') + 0;
        return $sum . ' %';
    }

    public static function getTotalNote($sid, $uid, $eid)
    {
        $survey = Survey::find($sid);
        if (!$survey || $survey->groupes->count() == 0) return 0;
        $groups = $survey->groupes;
        $sum = 0;
        foreach ($groups as $group) {
            $grpNote = floatval(self::getGrpNote($group->id, $uid, $eid));
            $sum += $grpNote * ($group->ponderation / 100);
        }

        return $sum;
    }

    public static function formated($number)
    {
        return number_format($number, 1, ".", "");
    }

    public static function  cutNum($num, $precision = 1){
        return floor($num).substr($num-floor($num),1,$precision+1);
    }

}
